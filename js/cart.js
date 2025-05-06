function initializeCartUpdates() {
    window.addToCart = function(itemId, itemName, itemPrice) {
        fetch('cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=add&item_id=${itemId}&item_name=${encodeURIComponent(itemName)}&item_price=${itemPrice}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(`${itemName} added to cart!`);
                updateCartDisplay();
            } else {
                showToast('Error adding to cart.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error adding to cart.', 'error');
        });
    };

    window.removeFromCart = function(itemId) {
        fetch('cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=remove&item_id=${itemId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Item removed from cart!');
                updateCartDisplay();
            } else {
                showToast('Error removing item.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error removing item.', 'error');
        });
    };

    function updateCartDisplay() {
        const cartItemsContainer = document.getElementById('cart-items');
        const cartTotal = document.getElementById('cart-total');
        if (!cartItemsContainer || !cartTotal) return;

        fetch('cart.php?action=get')
            .then(response => response.json())
            .then(data => {
                if (data.items.length === 0) {
                    cartItemsContainer.innerHTML = '<p>Your cart is empty.</p>';
                    cartTotal.textContent = 'Total: ৳0.00';
                } else {
                    cartItemsContainer.innerHTML = data.items.map(item => `
                        <div class="cart-item">
                            <span>${item.name} (x${item.quantity}) - ৳${(item.price * item.quantity).toFixed(2)}</span>
                            <button onclick="removeFromCart(${item.menu_item_id})">Remove</button>
                        </div>
                    `).join('');
                    cartItemsContainer.innerHTML += '<p>Suggestion: Add a drink for ৳20</p>';
                    cartTotal.textContent = `Total: ৳${data.total.toFixed(2)}`;
                }
            })
            .catch(error => console.error('Error updating cart:', error));
    }
}