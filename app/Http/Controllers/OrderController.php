<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\MercadoPagoService;

class OrderController extends Controller
{
    protected $mercadoPagoService;

    public function __construct(MercadoPagoService $mercadoPagoService)
    {
        $this->mercadoPagoService = $mercadoPagoService;
    }

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->with(['items.product', 'items.variant'])
            ->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    public function checkout()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para continuar');
        }

        $cart = Cart::where('user_id', Auth::id())->first();
        if (!$cart || $cart->items()->count() == 0) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío');
        }

        $cartItems = $cart->items()->with(['product', 'variant'])->get();
        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });
        
        $shipping = 0; // Envío gratuito por defecto
        $total = $subtotal + $shipping;

        return view('orders.checkout', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string',
            'shipping_state' => 'required|string',
            'shipping_zip' => 'required|string',
            'shipping_phone' => 'required|string',
            'payment_method' => 'required|in:mercadopago'
        ]);

        if (!Auth::check()) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        $cart = Cart::where('user_id', Auth::id())->first();
        if (!$cart || $cart->items()->count() == 0) {
            return response()->json(['error' => 'Carrito vacío'], 400);
        }

        try {
            DB::beginTransaction();

            // Crear la orden
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . time() . '-' . Auth::id(),
                'status' => 'pending',
                'total' => 0, // Se calculará después
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_state' => $request->shipping_state,
                'shipping_zip' => $request->shipping_zip,
                'shipping_phone' => $request->shipping_phone,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending'
            ]);

            // Crear los items de la orden
            $cartItems = $cart->items()->with(['product', 'variant'])->get();
            $total = 0;

            foreach ($cartItems as $cartItem) {
                $price = $cartItem->price;
                $subtotal = $price * $cartItem->quantity;
                $total += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'variant_id' => $cartItem->variant_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $price,
                    'subtotal' => $subtotal
                ]);
            }

            // Actualizar el total de la orden
            $order->update(['total' => $total]);

            // Crear preferencia de pago en MercadoPago
            $preference = $this->mercadoPagoService->createPreference($order);

            // Limpiar el carrito
            $cart->items()->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'preference_id' => $preference['id'],
                'init_point' => $preference['init_point']
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al procesar la orden: ' . $e->getMessage()], 500);
        }
    }

    public function webhook(Request $request)
    {
        try {
            $data = $request->all();
            
            // Verificar que es una notificación válida de MercadoPago
            if (!isset($data['type']) || !isset($data['data']['id'])) {
                return response()->json(['error' => 'Datos inválidos'], 400);
            }

            $paymentId = $data['data']['id'];
            $payment = $this->mercadoPagoService->getPayment($paymentId);

            if (!$payment) {
                return response()->json(['error' => 'Pago no encontrado'], 404);
            }

            // Buscar la orden por external_reference
            $order = Order::where('order_number', $payment['external_reference'])->first();
            if (!$order) {
                return response()->json(['error' => 'Orden no encontrada'], 404);
            }

            // Actualizar el estado de la orden según el estado del pago
            switch ($payment['status']) {
                case 'approved':
                    $order->update([
                        'status' => 'paid',
                        'payment_status' => 'paid',
                        'payment_id' => $paymentId
                    ]);
                    break;
                    
                case 'rejected':
                    $order->update([
                        'status' => 'cancelled',
                        'payment_status' => 'failed',
                        'payment_id' => $paymentId
                    ]);
                    break;
                    
                case 'pending':
                    $order->update([
                        'status' => 'pending',
                        'payment_status' => 'pending',
                        'payment_id' => $paymentId
                    ]);
                    break;
                    
                case 'in_process':
                    $order->update([
                        'status' => 'processing',
                        'payment_status' => 'processing',
                        'payment_id' => $paymentId
                    ]);
                    break;
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en webhook: ' . $e->getMessage()], 500);
        }
    }

    public function success(Request $request)
    {
        $orderId = $request->get('external_reference');
        $order = Order::where('order_number', $orderId)->first();
        
        if (!$order) {
            return redirect()->route('home')->with('error', 'Orden no encontrada');
        }

        return view('payment.success', compact('order'));
    }

    public function failure(Request $request)
    {
        $orderId = $request->get('external_reference');
        $order = Order::where('order_number', $orderId)->first();
        
        if (!$order) {
            return redirect()->route('home')->with('error', 'Orden no encontrada');
        }

        return view('payment.failure', compact('order'));
    }

    public function pending(Request $request)
    {
        $orderId = $request->get('external_reference');
        $order = Order::where('order_number', $orderId)->first();
        
        if (!$order) {
            return redirect()->route('home')->with('error', 'Orden no encontrada');
        }

        return view('payment.pending', compact('order'));
    }
} 