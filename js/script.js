document.addEventListener('DOMContentLoaded', () => {
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach((item, index) => {
        setTimeout(() => {
            item.classList.add('visible');
        }, index * 100);
    });

    const tracker = document.querySelector('.tracker');
    if (tracker) {
        const trackerSteps = document.querySelectorAll('.tracker-step');
        const trackerProgress = document.querySelector('.tracker-progress');
        let currentStep = 0;

        const statuses = ['Order Placed', 'Prep', 'Bake', 'Box', 'Delivery'];
        const updateTracker = () => {
            if (currentStep < trackerSteps.length) {
                trackerSteps[currentStep].classList.add('active');
                trackerProgress.style.width = `${(currentStep / (trackerSteps.length - 1)) * 100}%`;

                setTimeout(() => {
                    trackerSteps[currentStep].classList.remove('active');
                    trackerSteps[currentStep].classList.add('completed');
                    currentStep++;
                    if (currentStep < trackerSteps.length) {
                        trackerSteps[currentStep].classList.add('active');
                    }
                }, 1000);
            }
        };

        updateTracker();
        const interval = setInterval(updateTracker, 5000);
        setTimeout(() => clearInterval(interval), (trackerSteps.length - 1) * 5000 + 1000);
    }
});

function addToCart(itemId, itemName, itemPrice) {
    fetch('cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=add&item_id=${itemId}&item_name=${encodeURIComponent(itemName)}&item_price=${itemPrice}`
    })
    .then(response => response.text())
    .then(data => {
        alert(`${itemName} added to cart!`);
        if (document.getElementById('cart-items')) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

function removeFromCart(itemId) {
    fetch('cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=remove&item_id=${itemId}`
    })
    .then(response => response.text())
    .then(data => {
        if (document.getElementById('cart-items')) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}