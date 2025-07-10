<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MercadoPagoService
{
    protected $publicKey;
    protected $accessToken;
    protected $sandbox;
    protected $baseUrl;

    public function __construct()
    {
        $this->publicKey = config('mercadopago.public_key');
        $this->accessToken = config('mercadopago.access_token');
        $this->sandbox = config('mercadopago.sandbox', true);
        $this->baseUrl = $this->sandbox 
            ? 'https://api.mercadopago.com/sandbox' 
            : 'https://api.mercadopago.com';
    }

    /**
     * Crear una preferencia de pago
     */
    public function createPreference($order)
    {
        try {
            $preferenceData = [
                'items' => $this->formatOrderItems($order),
                'payer' => [
                    'name' => $order->user->name,
                    'email' => $order->user->email,
                ],
                'back_urls' => [
                    'success' => route('payment.success'),
                    'failure' => route('payment.failure'),
                    'pending' => route('payment.pending'),
                ],
                'auto_return' => 'approved',
                'external_reference' => $order->order_number,
                'notification_url' => route('webhooks.mercadopago'),
                'expires' => true,
                'expiration_date_to' => now()->addHours(24)->toISOString(),
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/checkout/preferences', $preferenceData);

            if ($response->successful()) {
                $data = $response->json();
                
                // Actualizar la orden con el preference_id
                $order->update([
                    'mercadopago_preference_id' => $data['id']
                ]);

                return [
                    'success' => true,
                    'preference_id' => $data['id'],
                    'init_point' => $data['init_point'],
                    'sandbox_init_point' => $data['sandbox_init_point'] ?? $data['init_point'],
                ];
            }

            Log::error('Error creating MercadoPago preference', [
                'response' => $response->json(),
                'order_id' => $order->id
            ]);

            return ['success' => false, 'error' => 'Error creating preference'];

        } catch (\Exception $e) {
            Log::error('Exception creating MercadoPago preference', [
                'error' => $e->getMessage(),
                'order_id' => $order->id
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Formatear items de la orden para Mercado Pago
     */
    protected function formatOrderItems($order)
    {
        $items = [];
        
        foreach ($order->items as $item) {
            $productName = $item->product->name;
            if ($item->variant) {
                $productName .= ' - ' . $item->variant->name;
            }
            
            $items[] = [
                'title' => $productName,
                'quantity' => $item->quantity,
                'unit_price' => $item->price,
                'currency_id' => 'ARS',
            ];
        }

        return $items;
    }

    /**
     * Obtener información de un pago
     */
    public function getPayment($paymentId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
            ])->get($this->baseUrl . '/v1/payments/' . $paymentId);

            if ($response->successful()) {
                return $response->json();
            }

            return null;

        } catch (\Exception $e) {
            Log::error('Error getting MercadoPago payment', [
                'error' => $e->getMessage(),
                'payment_id' => $paymentId
            ]);

            return null;
        }
    }

    /**
     * Procesar notificación webhook
     */
    public function processWebhook($data)
    {
        try {
            if (isset($data['data']['id'])) {
                $paymentId = $data['data']['id'];
                $payment = $this->getPayment($paymentId);

                if ($payment) {
                    return $this->updateOrderStatus($payment);
                }
            }

            return false;

        } catch (\Exception $e) {
            Log::error('Error processing MercadoPago webhook', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);

            return false;
        }
    }

    /**
     * Actualizar estado de la orden basado en el pago
     */
    public function updateOrderStatus($payment)
    {
        $orderNumber = $payment['external_reference'] ?? null;
        
        if (!$orderNumber) {
            return false;
        }

        $order = \App\Models\Order::where('order_number', $orderNumber)->first();
        
        if (!$order) {
            return false;
        }

        $status = $payment['status'];
        $paymentStatus = 'pending';

        switch ($status) {
            case 'approved':
                $paymentStatus = 'paid';
                $order->update([
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                    'mercadopago_payment_id' => $payment['id']
                ]);
                break;

            case 'rejected':
                $paymentStatus = 'failed';
                $order->update([
                    'payment_status' => 'failed',
                    'mercadopago_payment_id' => $payment['id']
                ]);
                break;

            case 'pending':
                $paymentStatus = 'pending';
                $order->update([
                    'payment_status' => 'pending',
                    'mercadopago_payment_id' => $payment['id']
                ]);
                break;

            case 'in_process':
                $paymentStatus = 'processing';
                $order->update([
                    'payment_status' => 'processing',
                    'mercadopago_payment_id' => $payment['id']
                ]);
                break;
        }

        return true;
    }

    /**
     * Obtener la clave pública para el frontend
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * Verificar si estamos en modo sandbox
     */
    public function isSandbox()
    {
        return $this->sandbox;
    }
} 