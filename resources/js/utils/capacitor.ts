import { App } from '@capacitor/app';

export function initCapacitor(): void {
    // Check if the Capacitor object is injected globally by the native container
    if (typeof (window as any).Capacitor !== 'undefined') {
        console.log('Capacitor detected. Registering native handlers...');

        // Register Android Back Button listener
        App.addListener('backButton', ({ canGoBack }) => {
            if (canGoBack) {
                window.history.back();
            } else {
                // At the root of the application, close the app
                App.exitApp();
            }
        });
    } else {
        console.log('Standard web context. Capacitor handlers skipped.');
    }
}
