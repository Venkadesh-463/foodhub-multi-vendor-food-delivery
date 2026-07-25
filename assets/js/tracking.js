/**
 * tracking.js — FoodHub Live Order Tracking
 * Polls the server for order status updates and reflects them in the UI.
 * Also handles simulated rider location for the map pin.
 */

const OrderTracking = (() => {
    const BASE = window.FOODHUB_BASE_URL || '/FoodHub/';

    // Status pipeline — order matters
    const STATUSES = [
        { key: 'pending',             label: 'Order Placed',       icon: 'fa-receipt' },
        { key: 'preparing',           label: 'Preparing Food',      icon: 'fa-utensils' },
        { key: 'ready_for_delivery',  label: 'Ready for Pickup',   icon: 'fa-box-check' },
        { key: 'out_for_delivery',    label: 'Out for Delivery',   icon: 'fa-motorcycle' },
        { key: 'delivered',           label: 'Delivered',           icon: 'fa-circle-check' },
    ];

    let pollInterval = null;
    let currentOrderId = null;
    let lastStatus = null;

    /* ── Bootstrap ─────────────────────────────────────── */
    function init() {
        const el = document.getElementById('trackingWidget');
        if (!el) return;

        currentOrderId = el.dataset.orderId;
        if (!currentOrderId) return;

        renderSteps(el.dataset.status || 'pending');
        startPolling();
    }

    /* ── Poll server every 8 s ──────────────────────────── */
    function startPolling() {
        pollInterval = setInterval(fetchStatus, 8000);
    }
    function stopPolling() {
        clearInterval(pollInterval);
        pollInterval = null;
    }

    async function fetchStatus() {
        try {
            const res  = await fetch(`${BASE}api/order/details.php?id=${currentOrderId}`);
            const data = await res.json();
            if (!data.success) return;

            const newStatus = data.order?.order_status;
            if (newStatus && newStatus !== lastStatus) {
                lastStatus = newStatus;
                renderSteps(newStatus);
                updateStatusBadge(newStatus);
                if (newStatus === 'delivered') {
                    stopPolling();
                    showDeliveredBanner();
                }
            }
        } catch { /* silent fail — network hiccup */ }
    }

    /* ── Render timeline steps ──────────────────────────── */
    function renderSteps(activeStatus) {
        const container = document.getElementById('trackingSteps');
        if (!container) return;

        const activeIdx = STATUSES.findIndex(s => s.key === activeStatus);
        container.innerHTML = STATUSES.map((step, i) => {
            const done   = i < activeIdx;
            const active = i === activeIdx;
            const cls    = done ? 'done' : active ? 'active' : 'pending';
            return `
            <div class="tracking-step ${cls}">
                <div class="tracking-step-icon">
                    <i class="fa-solid ${done ? 'fa-check' : step.icon}"></i>
                </div>
                <div class="tracking-step-info">
                    <div class="tracking-step-label">${step.label}</div>
                    ${active ? '<div class="tracking-step-sub">In progress…</div>' : ''}
                    ${done   ? '<div class="tracking-step-sub">Complete</div>' : ''}
                </div>
            </div>`;
        }).join('');
    }

    /* ── Update the status badge at the top ─────────────── */
    function updateStatusBadge(status) {
        const badge = document.getElementById('orderStatusBadge');
        if (!badge) return;
        badge.textContent = status.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
        badge.className = 'order-status-badge status-' + status;
    }

    /* ── Show a celebration banner when delivered ────────── */
    function showDeliveredBanner() {
        const banner = document.getElementById('deliveredBanner');
        if (banner) {
            banner.style.display = 'flex';
            banner.classList.add('animate-in');
        }
    }

    /* ── ETA Countdown (display only) ──────────────────── */
    function startETACountdown(minutes) {
        const el = document.getElementById('etaCountdown');
        if (!el) return;
        let remaining = minutes * 60; // seconds
        const tick = () => {
            if (remaining <= 0) { el.textContent = 'Arriving now!'; return; }
            const m = Math.floor(remaining / 60);
            const s = remaining % 60;
            el.textContent = `${m}:${String(s).padStart(2, '0')} min`;
            remaining--;
            setTimeout(tick, 1000);
        };
        tick();
    }

    return { init, startETACountdown };
})();

document.addEventListener('DOMContentLoaded', OrderTracking.init);
