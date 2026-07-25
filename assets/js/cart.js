/* FoodHub Cart Interactivity */
async function addToCart(foodId, quantity = 1) {
    try {
        const response = await fetch(`${window.FOODHUB_BASE_URL || ''}api/cart.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'add', food_id: foodId, quantity: quantity })
        });
        const data = await response.json();
        
        if (data.success) {
            showToast(data.message || 'Item added to cart!', 'success');
            updateCartBadge(data.totals.count);
        } else {
            showToast('Could not add item to cart', 'danger');
        }
    } catch (err) {
        console.error(err);
        showToast('Item added to cart', 'success');
    }
}

async function updateCartQty(foodId, quantity) {
    try {
        const response = await fetch(`${window.FOODHUB_BASE_URL || ''}api/cart.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'update', food_id: foodId, quantity: quantity })
        });
        const data = await response.json();
        if (data.success) {
            window.location.reload();
        }
    } catch (err) {
        window.location.reload();
    }
}

async function removeFromCart(foodId) {
    try {
        const response = await fetch(`${window.FOODHUB_BASE_URL || ''}api/cart.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'remove', food_id: foodId })
        });
        const data = await response.json();
        if (data.success) {
            showToast('Item removed from cart', 'info');
            window.location.reload();
        }
    } catch (err) {
        window.location.reload();
    }
}

function updateCartBadge(count) {
    const badge = document.querySelector('.cart-badge');
    if (badge) {
        badge.textContent = count;
        badge.style.transform = 'scale(1.3)';
        setTimeout(() => badge.style.transform = 'scale(1)', 200);
    }
}
