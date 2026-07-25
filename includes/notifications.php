<?php
/**
 * includes/notifications.php
 * Renders the notification dropdown HTML (bell icon + slide-down panel).
 * Included inside navbar.php whenever a user is logged in.
 */
if (!isLoggedIn()) return;
?>
<!-- Notification Bell -->
<div class="notif-wrapper" style="position:relative;">
    <button id="notifBell" class="notif-bell-btn" title="Notifications" aria-label="Notifications">
        <i class="fa-solid fa-bell"></i>
        <span id="notifBadge" class="notif-badge" style="display:none;">0</span>
    </button>

    <!-- Dropdown Panel -->
    <div id="notifDropdown" class="notif-dropdown">
        <div class="notif-dropdown-header">
            <span class="notif-dropdown-title"><i class="fa-solid fa-bell"></i> Notifications</span>
            <button id="markAllRead" class="notif-mark-all-btn">Mark all read</button>
        </div>
        <div id="notifList" class="notif-list">
            <div style="text-align:center;padding:1.5rem;color:rgba(255,255,255,.3);font-size:.85rem;">
                <i class="fa-solid fa-spinner fa-spin" style="font-size:1.2rem;display:block;margin-bottom:.5rem;"></i>
                Loading…
            </div>
        </div>
        <div class="notif-dropdown-footer">
            <a href="<?= BASE_URL ?>user/notifications.php" class="notif-view-all">View all notifications</a>
        </div>
    </div>
</div>

<style>
/* Notification Bell */
.notif-bell-btn {
    position: relative; background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1);
    border-radius: 10px; width: 40px; height: 40px;
    display: flex; align-items: center; justify-content: center;
    color: rgba(255,255,255,.65); font-size: .95rem;
    cursor: pointer; transition: all .2s;
}
.notif-bell-btn:hover { background: rgba(255,255,255,.1); color: #fff; }
.notif-badge {
    position: absolute; top: -5px; right: -5px; min-width: 18px; height: 18px;
    background: #e74c3c; color: #fff; font-size: .65rem; font-weight: 700;
    border-radius: 10px; display: flex; align-items: center; justify-content: center;
    padding: 0 4px; border: 2px solid #0f0c29;
}

/* Dropdown */
.notif-dropdown {
    position: absolute; right: 0; top: calc(100% + 10px);
    width: 360px; max-height: 480px; display: flex; flex-direction: column;
    background: rgba(18,18,40,.97); border: 1px solid rgba(255,255,255,.1);
    border-radius: 16px; box-shadow: 0 20px 50px rgba(0,0,0,.5);
    backdrop-filter: blur(20px); overflow: hidden;
    opacity: 0; transform: translateY(-8px) scale(.97); pointer-events: none;
    transition: all .25s cubic-bezier(.34,1.56,.64,1); z-index: 1050;
}
.notif-dropdown.open { opacity: 1; transform: translateY(0) scale(1); pointer-events: auto; }

/* Header */
.notif-dropdown-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1rem 1.25rem; border-bottom: 1px solid rgba(255,255,255,.08); flex-shrink: 0;
}
.notif-dropdown-title { font-size: .9rem; font-weight: 700; color: #fff; display: flex; align-items: center; gap: .5rem; }
.notif-dropdown-title i { color: #f7931e; }
.notif-mark-all-btn {
    font-size: .72rem; font-weight: 600; color: #a29bfe; background: none;
    border: none; cursor: pointer; padding: 0; transition: opacity .2s;
}
.notif-mark-all-btn:hover { opacity: .75; }

/* List */
.notif-list { flex: 1; overflow-y: auto; max-height: 340px; }
.notif-list::-webkit-scrollbar { width: 4px; }
.notif-list::-webkit-scrollbar-thumb { background: rgba(255,255,255,.15); border-radius: 4px; }

/* Item */
.notif-item {
    display: flex; align-items: flex-start; gap: .85rem;
    padding: .9rem 1.25rem; border-bottom: 1px solid rgba(255,255,255,.05);
    cursor: pointer; transition: background .15s;
}
.notif-item:last-child { border-bottom: none; }
.notif-item:hover { background: rgba(255,255,255,.04); }
.notif-item.unread { background: rgba(108,92,231,.08); }
.notif-item.unread::before { /* left accent dot handled via icon */ }

/* Notification icon */
.notif-icon {
    width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: .9rem;
}
.notif-icon.order    { background: rgba(247,147,30,.15); color: #f7931e; }
.notif-icon.payment  { background: rgba(0,184,148,.15);  color: #00b894; }
.notif-icon.delivery { background: rgba(0,176,155,.15);  color: #00b09b; }
.notif-icon.promo    { background: rgba(108,92,231,.15); color: #a29bfe; }
.notif-icon.system   { background: rgba(255,255,255,.07); color: rgba(255,255,255,.4); }

.notif-body { flex: 1; min-width: 0; }
.notif-title { font-size: .85rem; font-weight: 600; color: #fff; margin-bottom: .15rem; }
.notif-msg   { font-size: .78rem; color: rgba(255,255,255,.5); line-height: 1.4; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.notif-time  { font-size: .7rem; color: rgba(255,255,255,.28); margin-top: .2rem; }

/* Footer */
.notif-dropdown-footer { padding: .75rem 1.25rem; border-top: 1px solid rgba(255,255,255,.08); text-align: center; flex-shrink: 0; }
.notif-view-all { font-size: .8rem; font-weight: 600; color: #a29bfe; text-decoration: none; transition: opacity .2s; }
.notif-view-all:hover { opacity: .75; }
</style>
