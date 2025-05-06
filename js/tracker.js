function initializeOrderTracker() {
    const tracker = document.querySelector('.tracker');
    if (!tracker) return;

    const trackerSteps = document.querySelectorAll('.tracker-step');
    const trackerProgress = document.querySelector('.tracker-progress');
    let currentStep = 0;

    const statuses = ['Order Placed', 'Prep', 'Bake', 'Box', 'Delivery'];

    function updateTracker() {
        if (currentStep >= trackerSteps.length) return;

        trackerSteps[currentStep].classList.add('active');
        trackerProgress.style.width = `${(currentStep / (trackerSteps.length - 1)) * 100}%`;

        setTimeout(() => {
            trackerSteps[currentStep].classList.remove('active');
            trackerSteps[currentStep].classList.add('completed');
            currentStep++;
            if (currentStep < trackerSteps.length) {
                trackerSteps[currentStep].classList.add('active');
                showToast(`Order status updated: ${statuses[currentStep]}`);
            }
        }, 1000);
    }

    updateTracker();
    const interval = setInterval(() => {
        if (currentStep < trackerSteps.length) {
            updateTracker();
        } else {
            clearInterval(interval);
        }
    }, 5000);

    // Simulate WebSocket updates (replace with real WebSocket in production)
    setTimeout(() => {
        if (currentStep < trackerSteps.length - 1) {
            currentStep = Math.min(currentStep + 1, trackerSteps.length - 1);
            updateTracker();
        }
    }, 10000);
}