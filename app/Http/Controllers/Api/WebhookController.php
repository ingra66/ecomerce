<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    protected $mercadoPagoService;

    public function __construct(MercadoPagoService $mercadoPagoService)
    {
        $this->mercadoPagoService = $mercadoPagoService;
    }

    /**
     * Procesar webhook de MercadoPago
     */
    public function mercadopago(Request $request): JsonResponse
    {
        try {
            Log::info('MercadoPago webhook received', [
                'data' => $request->all()
            ]);

            $data = $request->all();
            
            // Verificar que sea una notificación válida
            if (!isset($data['type']) || $data['type'] !== 'payment') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tipo de notificación no válido'
                ], 400);
            }

            // Procesar la notificación
            $processed = $this->mercadoPagoService->processWebhook($data);

            if ($processed) {
                return response()->json([
                    'success' => true,
                    'message' => 'Webhook procesado exitosamente'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al procesar webhook'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Error processing MercadoPago webhook', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Verificar estado de pago
     */
    public function checkPaymentStatus(Request $request): JsonResponse
    {
        $request->validate([
            'payment_id' => 'required|string'
        ]);

        try {
            $payment = $this->mercadoPagoService->getPayment($request->payment_id);
            
            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pago no encontrado'
                ], 404);
            }

            // Actualizar estado de la orden
            $this->mercadoPagoService->updateOrderStatus($payment);

            return response()->json([
                'success' => true,
                'data' => [
                    'payment_id' => $payment['id'],
                    'status' => $payment['status'],
                    'external_reference' => $payment['external_reference'] ?? null,
                    'amount' => $payment['transaction_amount'] ?? 0,
                    'currency' => $payment['currency'] ?? 'ARS'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error checking payment status', [
                'error' => $e->getMessage(),
                'payment_id' => $request->payment_id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al verificar estado del pago'
            ], 500);
        }
    }
} 