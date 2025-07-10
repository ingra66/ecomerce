<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Coupon;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    protected $mercadoPagoService;

    public function __construct(MercadoPagoService $mercadoPagoService)
    {
        $this->mercadoPagoService = $mercadoPagoService;
    }

    /**
     * Obtener órdenes del usuario
     */
    public function index(): JsonResponse
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        $orders = Order::with(['items.product.images'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $orders->items(),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ]
        ]);
    }

    /**
     * Obtener orden específica
     */
    public function show($id): JsonResponse
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        $order = Order::with(['items.product.images', 'items.product.variants'])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    /**
     * Crear orden desde el carrito
     */
    public function checkout(Request $request): JsonResponse
    {
        $request->validate([
            'shipping_address' => 'required|array',
            'shipping_address.name' => 'required|string',
            'shipping_address.email' => 'required|email',
            'shipping_address.phone' => 'required|string',
            'shipping_address.address' => 'required|string',
            'shipping_address.city' => 'required|string',
            'shipping_address.state' => 'required|string',
            'shipping_address.postal_code' => 'required|string',
            'billing_address' => 'sometimes|array',
            'coupon_code' => 'sometimes|string',
            'notes' => 'sometimes|string'
        ]);

        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        $cart = Cart::getCurrent();
        
        if (!$cart || $cart->is_empty) {
            return response()->json([
                'success' => false,
                'message' => 'El carrito está vacío'
            ], 400);
        }

        // Verificar stock de todos los productos
        foreach ($cart->items as $item) {
            $product = $item->product;
            if ($product->stock < $item->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Stock insuficiente para {$product->name}"
                ], 400);
            }
        }

        // Validar cupón si se proporciona
        $coupon = null;
        if ($request->has('coupon_code')) {
            $coupon = Coupon::findByCode($request->coupon_code);
            if (!$coupon) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cupón no válido'
                ], 400);
            }
            
            if (!$coupon->isValidForAmount($cart->total)) {
                return response()->json([
                    'success' => false,
                    'message' => 'El cupón no es válido para este monto'
                ], 400);
            }
        }

        try {
            DB::beginTransaction();

            // Usar dirección de facturación si se proporciona, sino usar la de envío
            $billingAddress = $request->get('billing_address', $request->shipping_address);

            // Crear la orden
            $order = $cart->convertToOrder(
                $user,
                $request->shipping_address,
                $billingAddress,
                $coupon
            );

            // Aplicar cupón si existe
            if ($coupon) {
                $discount = $cart->calculateCouponDiscount($coupon);
                $order->update(['discount_amount' => $discount]);
                $order->calculateTotals();
                
                // Incrementar uso del cupón
                $coupon->incrementUsage();
            }

            // Crear preferencia de pago en MercadoPago
            $paymentResult = $this->mercadoPagoService->createPreference($order);

            if (!$paymentResult['success']) {
                throw new \Exception('Error al crear preferencia de pago');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Orden creada exitosamente',
                'data' => [
                    'order' => $order->load(['items.product.images']),
                    'payment' => [
                        'preference_id' => $paymentResult['preference_id'],
                        'init_point' => $paymentResult['init_point'],
                        'sandbox_init_point' => $paymentResult['sandbox_init_point'],
                        'public_key' => $this->mercadoPagoService->getPublicKey()
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la orden: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validar cupón
     */
    public function validateCoupon(Request $request): JsonResponse
    {
        $request->validate([
            'coupon_code' => 'required|string'
        ]);

        $cart = Cart::getCurrent();
        
        if (!$cart || $cart->is_empty) {
            return response()->json([
                'success' => false,
                'message' => 'El carrito está vacío'
            ], 400);
        }

        $coupon = Coupon::findByCode($request->coupon_code);
        
        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Cupón no válido'
            ], 400);
        }

        if (!$coupon->isValidForAmount($cart->total)) {
            return response()->json([
                'success' => false,
                'message' => 'El cupón no es válido para este monto'
            ], 400);
        }

        $discount = $coupon->calculateDiscount($cart->total);
        $totalWithDiscount = $cart->total - $discount;

        return response()->json([
            'success' => true,
            'message' => 'Cupón válido',
            'data' => [
                'coupon' => [
                    'code' => $coupon->code,
                    'name' => $coupon->name,
                    'description' => $coupon->description,
                    'discount' => $discount,
                    'formatted_discount' => $coupon->formatted_discount,
                    'minimum_amount' => $coupon->formatted_minimum_amount
                ],
                'cart' => [
                    'subtotal' => $cart->total,
                    'discount' => $discount,
                    'total' => $totalWithDiscount,
                    'formatted_subtotal' => $cart->formatted_total,
                    'formatted_discount' => '$' . number_format($discount / 100, 2, ',', '.'),
                    'formatted_total' => '$' . number_format($totalWithDiscount / 100, 2, ',', '.')
                ]
            ]
        ]);
    }

    /**
     * Obtener estados de pago
     */
    public function paymentStatus($orderId): JsonResponse
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        $order = Order::where('user_id', $user->id)
            ->findOrFail($orderId);

        // Si tiene payment_id de MercadoPago, obtener estado actual
        if ($order->mercadopago_payment_id) {
            $payment = $this->mercadoPagoService->getPayment($order->mercadopago_payment_id);
            
            if ($payment) {
                // Actualizar estado si es diferente
                $this->mercadoPagoService->updateOrderStatus($payment);
                $order->refresh();
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'order' => $order,
                'payment_status' => $order->payment_status,
                'order_status' => $order->status
            ]
        ]);
    }
} 