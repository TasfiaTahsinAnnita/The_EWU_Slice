function initializeCartUpdates() {
    // Avoid overwriting existing global functions
    if (window.addToCart || window.removeFromCart) {
        console.warn('addToCart or removeFromCart is already defined on window. Skipping initialization.');
        return;
    }

    // Define addToCart function
    window.addToCart = function(itemId, itemName, itemPrice) {
        if (!itemId || !itemName || !itemPrice) {
            console.error('Invalid parameters for addToCart:', { itemId, itemName, itemPrice });
            showToast('Invalid item data.', 'error');
            return;
        }

        fetch('cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=add&item_id=${itemId}&item_name=${encodeURIComponent(itemName)}&item_price=${itemPrice}`
        })
        .then(response => {
            if (!response.ok) {
                // Log the raw response text for debugging
                return response.text().then(text => {
                    throw new Error(`HTTP error! Status: ${response.status}, Response: ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data && typeof data.success !== 'undefined' && data.success) {
                showToast(`${itemName} added to cart!`, 'success');
                updateCartDisplay();
                showViewCartPopup();
            } else {
                console.error('Add to cart failed:', data);
                showToast(`Failed to add ${itemName} to cart. ${data?.error || ''}`, 'error');
            }
        })
        .catch(error => {
            console.error('Fetch error in addToCart:', error);
            showToast('Error adding to cart. Please try again.', 'error');
        });
    };

    // Define removeFromCart function
    window.removeFromCart = function(itemId) {
        if (!itemId) {
            console.error('Invalid itemId for removeFromCart:', itemId);
            showToast('Invalid item ID.', 'error');
            return;
        }

        fetch('cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=remove&item_id=${itemId}`
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`HTTP error! Status: ${response.status}, Response: ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data && typeof data.success !== 'undefined' && data.success) {
                showToast('Item removed from cart!', 'success');
                updateCartDisplay();
            } else {
                console.error('Remove from cart failed:', data);
                showToast('Error removing item.', 'error');
            }
        })
        .catch(error => {
            console.error('Fetch error in removeFromCart:', error);
            showToast('Error removing item. Please try again.', 'error');
        });
    };

    // Update cart display function
    function updateCartDisplay() {
        const cartItemsContainer = document.getElementById('cart-items');
        const cartTotal = document.getElementById('cart-total');

        if (!cartItemsContainer || !cartTotal) {
            console.warn('Cart elements not found:', { cartItemsContainer, cartTotal });
            return;
        }

        fetch('cart.php?action=get')
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(`HTTP error! Status: ${response.status}, Response: ${text}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data && typeof data.items !== 'undefined' && typeof data.total !== 'undefined') {
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
                } else {
                    throw new Error('Invalid data format from server');
                }
            })
            .catch(error => {
                console.error('Error updating cart:', error);
                cartItemsContainer.innerHTML = '<p>Error loading cart. Please try again.</p>';
                cartTotal.textContent = 'Total: ৳0.00';
            });
    }

    // Show "View Cart" popup after adding an item
    function showViewCartPopup() {
        const popup = document.createElement('div');
        popup.className = 'cart-popup';
        popup.innerHTML = `
            <p>Item added to cart!</p>
            <button onclick="window.location.href='cart.php'">View Cart</button>
            <button onclick="this.parentElement.remove()">Close</button>
        `;
        document.body.appendChild(popup);

        // Auto-close after 5 seconds if not interacted with
        setTimeout(() => popup.remove(), 5000);
    }

    // Ensure DOM is ready before initial update
    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        updateCartDisplay();
    } else {
        document.addEventListener('DOMContentLoaded', updateCartDisplay);
    }
}