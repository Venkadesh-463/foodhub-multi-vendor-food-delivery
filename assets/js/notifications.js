/**
 * notifications.js — FoodHub In-App Notification Centre
 * Handles: toast alerts, notification bell dropdown, mark-as-read,
 *          and optional browser push notifications.
 */

const FoodHubNotifications = (() => {
    const BASE = window.FOODHUB_BASE_URL || '/FoodHub/';
    let pollInterval = null;
    let unreadCount  = 0;

    /* ─────────────────────────────────────────────────────
       TOAST SYSTEM
       Call: showToast('message', 'success' | 'danger' | 'info' | 'warning')
       ───────────────────────────────────────────────────── */
    function showToast(message, type = 'info', duration = 4000) {
        let container = document.getElementById('toastContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toastContainer';
            container.style.cssText = 'position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;display:flex;flex-direction:column;gap:.6rem;max-width:360px;';
            document.body.appendChild(container);
        }

        const icons = { success: 'fa-circle-check', danger: 'fa-circle-exclamation', info: 'fa-circle-info', warning: 'fa-triangle-exclamation' };
        const colors = { success: '#00b894', danger: '#e74c3c', info: '#6c5ce7', warning: '#f39c12' };

        const toast = document.createElement('div');
        toast.style.cssText = `
            display:flex;align-items:flex-start;gap:.75rem;padding:.9rem 1.1rem;
            background:rgba(18,18,35,0.95);border:1px solid rgba(255,255,255,0.1);
            border-left:4px solid ${colors[type] || colors.info};border-radius:12px;
            box-shadow:0 8px 24px rgba(0,0,0,.4);backdrop-filter:blur(12px);
            animation:toastIn .3s cubic-bezier(.34,1.56,.64,1);
        `;
        toast.innerHTML = `
            <i class="fa-solid ${icons[type] || icons.info}" style="color:${colors[type]};margin-top:.1rem;flex-shrink:0;"></i>
            <span style="font-size:.875rem;color:#e0e0e0;flex:1;line-height:1.5;">${message}</span>
            <button onclick="this.closest('div').remove()" style="background:none;border:none;color:rgba(255,255,255,.3);cursor:pointer;font-size:1rem;padding:0;line-height:1;">&times;</button>
        `;

        // Add keyframe if not present
        if (!document.getElementById('toastKF')) {
            const s = document.createElement('style');
            s.id = 'toastKF';
            s.textContent = '@keyframes toastIn{from{opacity:0;transform:translateX(30px)}to{opacity:1;transform:translateX(0)}}';
            document.head.appendChild(s);
        }

        container.appendChild(toast);
        setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'opacity .3s'; setTimeout(() => toast.remove(), 300); }, duration);
    }

    /* ─────────────────────────────────────────────────────
       NOTIFICATION BELL DROPDOWN
       ───────────────────────────────────────────────────── */
    function initBell() {
        const bell    = document.getElementById('notifBell');
        const dropdown= document.getElementById('notifDropdown');
        if (!bell || !dropdown) return;

        bell.addEventListener('click', e => {
            e.stopPropagation();
            dropdown.classList.toggle('open');
            if (dropdown.classList.contains('open')) fetchNotifications();
        });

        document.addEventListener('click', e => {
            if (!bell.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove('open');
            }
        });

        // Mark all read button
        const markAllBtn = document.getElementById('markAllRead');
        if (markAllBtn) markAllBtn.addEventListener('click', markAllAsRead);
    }

    /* ── Fetch unread notifications from server ─────────── */
    async function fetchNotifications() {
        const list = document.getElementById('notifList');
        if (!list) return;
        list.innerHTML = '<div style="text-align:center;padding:1.5rem;color:rgba(255,255,255,.3);"><i class="fa fa-spinner fa-spin"></i></div>';

        try {
            const res  = await fetch(BASE + 'api/notifications/list.php');
            const data = await res.json();
            if (!data.success || !data.notifications?.length) {
                list.innerHTML = '<div style="text-align:center;padding:1.5rem;color:rgba(255,255,255,.3);font-size:.85rem;"><i class="fa-solid fa-bell-slash" style="font-size:1.5rem;display:block;margin-bottom:.5rem;"></i>No notifications</div>';
                return;
            }
            renderNotifications(data.notifications, list);
            updateBellCount(data.unread_count || 0);
        } catch {
            list.innerHTML = '<div style="text-align:center;padding:1rem;color:#ff8fa3;font-size:.8rem;">Failed to load</div>';
        }
    }

    function renderNotifications(items, container) {
        container.innerHTML = items.map(n => `
            <div class="notif-item ${n.is_read ? '' : 'unread'}" data-id="${n.id}" onclick="FoodHubNotifications.markRead(${n.id}, this)">
                <div class="notif-icon ${n.type}"><i class="fa-solid ${getNotifIcon(n.type)}"></i></div>
                <div class="notif-body">
                    <div class="notif-title">${n.title}</div>
                    <div class="notif-msg">${n.message}</div>
                    <div class="notif-time">${n.time_ago}</div>
                </div>
            </div>`).join('');
    }

    function getNotifIcon(type) {
        const map = { order: 'fa-bag-shopping', payment: 'fa-credit-card', promo: 'fa-tag', delivery: 'fa-motorcycle', system: 'fa-bell' };
        return map[type] || 'fa-bell';
    }

    /* ── Mark single notification read ─────────────────── */
    async function markRead(id, el) {
        el?.classList.remove('unread');
        unreadCount = Math.max(0, unreadCount - 1);
        updateBellCount(unreadCount);
        try {
            await fetch(BASE + 'api/notifications/mark-read.php', {
                method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id })
            });
        } catch { /* silent */ }
    }

    /* ── Mark all read ─────────────────────────────────── */
    async function markAllAsRead() {
        document.querySelectorAll('.notif-item.unread').forEach(el => el.classList.remove('unread'));
        updateBellCount(0);
        try {
            await fetch(BASE + 'api/notifications/mark-all-read.php', { method: 'POST' });
        } catch { /* silent */ }
    }

    /* ── Update the red badge count ─────────────────────── */
    function updateBellCount(count) {
        unreadCount = count;
        const badge = document.getElementById('notifBadge');
        if (!badge) return;
        badge.textContent = count;
        badge.style.display = count > 0 ? 'flex' : 'none';
    }

    /* ── Poll for new notifications every 30 s ──────────── */
    async function pollUnreadCount() {
        try {
            const res  = await fetch(BASE + 'api/notifications/unread-count.php');
            const data = await res.json();
            if (data.success) updateBellCount(data.count || 0);
        } catch { /* silent */ }
    }

    function startPolling() {
        pollUnreadCount();
        pollInterval = setInterval(pollUnreadCount, 30000);
    }

    /* ── Browser Push Notifications ─────────────────────── */
    async function requestPushPermission() {
        if (!('Notification' in window)) return;
        if (Notification.permission === 'granted') return;
        const perm = await Notification.requestPermission();
        if (perm === 'granted') console.log('[FoodHub] Push notifications granted.');
    }

    function sendBrowserNotification(title, body, icon = BASE + 'assets/images/icons/logo-192.png') {
        if (Notification.permission !== 'granted') return;
        new Notification(title, { body, icon });
    }

    /* ── Public Init ────────────────────────────────────── */
    function init() {
        initBell();
        if (document.getElementById('notifBell')) startPolling();
    }

    // Expose showToast globally so PHP-rendered scripts can call it
    window.showToast = showToast;

    return { init, showToast, markRead, markAllAsRead, sendBrowserNotification, requestPushPermission };
})();

document.addEventListener('DOMContentLoaded', FoodHubNotifications.init);
