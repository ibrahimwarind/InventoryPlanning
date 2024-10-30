let deferredPrompt;
    window.addEventListener('beforeinstallprompt', (e) => {
        deferredPrompt = e;
    });

    const installApp = document.getElementById('installApp');
    installApp.addEventListener('click', async () => {
        if (deferredPrompt !== null) {
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            if (outcome === 'accepted') {
                deferredPrompt = null;
            }
        }
    });

if("serviceWorker" in navigator)
{
	navigator.serviceWorker.register("sw.js").then(registration =>{
		console.log("SM Registered!");
		console.log(registration);
	}).catch(error =>{
     console.log("SM Failed!");
		console.log(error);
	});
}