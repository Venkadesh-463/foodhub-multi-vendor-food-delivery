/* FoodHub Core JavaScript */
document.addEventListener('DOMContentLoaded', () => {
    console.log('FoodHub Web App Initialized');
});

// Dynamic Toast Notification System
function showToast(message, type = 'info') {
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    
    let icon = 'fa-info-circle';
    if (type === 'success') icon = 'fa-check-circle';
    if (type === 'danger' || type === 'error') icon = 'fa-exclamation-circle';

    toast.innerHTML = `<i class="fas ${icon}"></i> <span>${message}</span>`;
    container.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}

// Toggle Wishlist Item
async function toggleWishlist(foodId, btnElement) {
    try {
        const response = await fetch(`${BASE_URL || ''}api/cart.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'wishlist_toggle', food_id: foodId })
        });
        const data = await response.json();
        showToast(data.message || 'Wishlist updated', 'success');
        if (btnElement) {
            btnElement.classList.toggle('active');
        }
    } catch (err) {
        showToast('Wishlist action completed', 'info');
    }
}
