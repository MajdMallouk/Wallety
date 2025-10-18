console.log("register-pwa.js loaded"); // Add at top

if ('serviceWorker' in navigator) {
    window.addEventListener('load', async () => {
        try {
            const reg = await navigator.serviceWorker.register('/sw.js');
            console.log('SW registered! Scope:', reg.scope);

            // Check if service worker is controlling the page
            if (reg.active) console.log('SW controlling this page');
            else console.log('SW installed but not controlling');
        }
        catch (err) {
            console.error('SW registration failed:', err);
        }
    });
} else {
    console.warn('Service Workers not supported');
}

let deferredPrompt;

// Listen for the beforeinstallprompt event
window.addEventListener('beforeinstallprompt', (e) => {
    // Prevent Chrome’s default mini-infobar
    e.preventDefault();
    deferredPrompt = e;

    // Show your custom “Install App” button
    const btn = document.getElementById('btnInstallPWA');
    btn.style.display = 'inline-flex';
    btn.addEventListener('click', async () => {
        // Hide your button
        btn.style.display = 'none';

        // Show Chrome install prompt
        deferredPrompt.prompt();

        // Wait for the user’s response
        const { outcome } = await deferredPrompt.userChoice;
        console.log('User response to install prompt:', outcome);

        deferredPrompt = null;
    });
});

// Register the service worker
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(reg => console.log('Service Worker registered:', reg.scope))
            .catch(err => console.error('SW registration failed:', err));
    });
}
