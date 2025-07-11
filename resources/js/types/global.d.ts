declare module 'alpinejs' {
    const Alpine: any;
    export default Alpine;
}

declare module '@mercadopago/sdk-js' {
    export class MercadoPago {
        constructor(publicKey: string);
        checkout(config: any): void;
    }
}

declare global {
    interface Window {
        Alpine: any;
        MercadoPago: any;
        initMercadoPago: (publicKey: string) => any;
        createMercadoPagoPreference: (orderData: any) => Promise<any>;
        openMercadoPagoCheckout: (preferenceId: string) => void;
        mercadopagoPublicKey: string;
    }
}

// Tipos para Alpine.js
declare global {
    interface AlpineComponent {
        $dispatch: (event: string, data?: any) => void;
    }
} 