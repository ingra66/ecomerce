import React from 'react';
import './bootstrap';
import Alpine from 'alpinejs';
import { MercadoPago } from '@mercadopago/sdk-js';
import ReactDOM from 'react-dom/client';
import { BrowserRouter } from 'react-router-dom';
import App from './components/App';

// Inicializar Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Configurar Mercado Pago
window.MercadoPago = MercadoPago;

// Función para inicializar Mercado Pago
window.initMercadoPago = function(publicKey) {
    const mp = new MercadoPago(publicKey);
    return mp;
};

// Función para crear preferencia de pago
window.createMercadoPagoPreference = async function(orderData) {
    try {
        const response = await fetch('/api/mercadopago/create-preference', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify(orderData)
        });

        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error creating preference:', error);
        return { success: false, error: error.message };
    }
};

// Función para abrir checkout de Mercado Pago
window.openMercadoPagoCheckout = function(preferenceId) {
    const mp = new MercadoPago(window.mercadopagoPublicKey);
    mp.checkout({
        preference: {
            id: preferenceId
        },
        render: {
            container: '.mercadopago-checkout',
            label: 'Pagar con Mercado Pago'
        }
    });
};

// Componente Alpine para el carrito
Alpine.data('cart', () => ({
    items: [],
    total: 0,
    loading: false,

    init() {
        this.loadCart();
    },

    async loadCart() {
        try {
            const response = await fetch('/api/cart');
            const data = await response.json();
            this.items = data.items || [];
            this.calculateTotal();
        } catch (error) {
            console.error('Error loading cart:', error);
        }
    },

    async addToCart(productId, quantity = 1, variants = {}) {
        this.loading = true;
        try {
            const response = await fetch('/api/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity,
                    variants: variants
                })
            });

            const data = await response.json();
            if (data.success) {
                this.loadCart();
                this.showNotification('Producto agregado al carrito');
            }
        } catch (error) {
            console.error('Error adding to cart:', error);
        } finally {
            this.loading = false;
        }
    },

    async removeFromCart(itemId) {
        try {
            const response = await fetch(`/api/cart/remove/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });

            const data = await response.json();
            if (data.success) {
                this.loadCart();
                this.showNotification('Producto removido del carrito');
            }
        } catch (error) {
            console.error('Error removing from cart:', error);
        }
    },

    async updateQuantity(itemId, quantity) {
        try {
            const response = await fetch(`/api/cart/update/${itemId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({ quantity: quantity })
            });

            const data = await response.json();
            if (data.success) {
                this.loadCart();
            }
        } catch (error) {
            console.error('Error updating quantity:', error);
        }
    },

    calculateTotal() {
        this.total = this.items.reduce((sum, item) => {
            return sum + (item.unit_price * item.quantity);
        }, 0);
    },

    showNotification(message) {
        // Implementar notificación (puedes usar Toastr, SweetAlert, etc.)
        console.log(message);
    }
}));

// Componente Alpine para productos
Alpine.data('product', () => ({
    selectedVariants: {},
    quantity: 1,

    selectVariant(type, value) {
        this.selectedVariants[type] = value;
    },

    addToCart(productId) {
        this.$dispatch('add-to-cart', {
            productId: productId,
            quantity: this.quantity,
            variants: this.selectedVariants
        });
    }
}));

// Inicializar React
const container = document.getElementById('app');
if (container) {
    const root = ReactDOM.createRoot(container);
    root.render(
        <React.StrictMode>
            <BrowserRouter>
                <App />
            </BrowserRouter>
        </React.StrictMode>
    );
} 