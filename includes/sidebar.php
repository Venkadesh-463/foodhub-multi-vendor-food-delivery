<?php
$currentRole = $_SESSION['user_role'] ?? 'user';
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
  <div style="margin-bottom: 2rem; padding: 0 0.5rem;">
    <h3 style="color: var(--white); font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px;">
      <?= ucfirst($currentRole) ?> Portal
    </h3>
    <p style="font-size: 0.85rem; color: var(--gray-400);">Welcome, <?= sanitize($_SESSION['user_name'] ?? 'User') ?></p>
  </div>

  <nav class="sidebar-menu">
    <?php if ($currentRole === ROLE_USER): ?>
      <a href="<?= BASE_URL ?>user/dashboard.php" class="sidebar-link <?= $currentPage === 'dashboard.php' ? 'active' : '' ?>">
        <i class="fas fa-th-large"></i> Dashboard
      </a>
      <a href="<?= BASE_URL ?>user/restaurants.php" class="sidebar-link <?= $currentPage === 'restaurants.php' ? 'active' : '' ?>">
        <i class="fas fa-utensils"></i> Browse Restaurants
      </a>
      <a href="<?= BASE_URL ?>user/orders.php" class="sidebar-link <?= $currentPage === 'orders.php' ? 'active' : '' ?>">
        <i class="fas fa-receipt"></i> My Orders
      </a>
      <a href="<?= BASE_URL ?>user/wishlist.php" class="sidebar-link <?= $currentPage === 'wishlist.php' ? 'active' : '' ?>">
        <i class="fas fa-heart"></i> Wishlist
      </a>
      <a href="<?= BASE_URL ?>user/profile.php" class="sidebar-link <?= $currentPage === 'profile.php' ? 'active' : '' ?>">
        <i class="fas fa-user-cog"></i> Account Profile
      </a>

    <?php elseif ($currentRole === ROLE_RESTAURANT): ?>
      <a href="<?= BASE_URL ?>restaurant/dashboard.php" class="sidebar-link <?= $currentPage === 'dashboard.php' ? 'active' : '' ?>">
        <i class="fas fa-chart-line"></i> Overview
      </a>
      <a href="<?= BASE_URL ?>restaurant/manage-food.php" class="sidebar-link <?= $currentPage === 'manage-food.php' ? 'active' : '' ?>">
        <i class="fas fa-hamburger"></i> Menu Items
      </a>
      <a href="<?= BASE_URL ?>restaurant/add-food.php" class="sidebar-link <?= $currentPage === 'add-food.php' ? 'active' : '' ?>">
        <i class="fas fa-plus-circle"></i> Add New Item
      </a>
      <a href="<?= BASE_URL ?>restaurant/orders.php" class="sidebar-link <?= $currentPage === 'orders.php' ? 'active' : '' ?>">
        <i class="fas fa-shopping-bag"></i> Store Orders
      </a>
      <a href="<?= BASE_URL ?>restaurant/profile.php" class="sidebar-link <?= $currentPage === 'profile.php' ? 'active' : '' ?>">
        <i class="fas fa-store-alt"></i> Store Settings
      </a>

    <?php elseif ($currentRole === ROLE_DELIVERY): ?>
      <a href="<?= BASE_URL ?>delivery/dashboard.php" class="sidebar-link <?= $currentPage === 'dashboard.php' ? 'active' : '' ?>">
        <i class="fas fa-tachometer-alt"></i> Dashboard
      </a>
      <a href="<?= BASE_URL ?>delivery/available-orders.php" class="sidebar-link <?= $currentPage === 'available-orders.php' ? 'active' : '' ?>">
        <i class="fas fa-box"></i> Active Deliveries
      </a>
      <a href="<?= BASE_URL ?>delivery/delivery-status.php" class="sidebar-link <?= $currentPage === 'delivery-status.php' ? 'active' : '' ?>">
        <i class="fas fa-map-marker-alt"></i> Trip History
      </a>

    <?php elseif ($currentRole === ROLE_ADMIN): ?>
      <a href="<?= BASE_URL ?>admin/dashboard.php" class="sidebar-link <?= $currentPage === 'dashboard.php' ? 'active' : '' ?>">
        <i class="fas fa-chart-pie"></i> Control Panel
      </a>
      <a href="<?= BASE_URL ?>admin/users.php" class="sidebar-link <?= $currentPage === 'users.php' ? 'active' : '' ?>">
        <i class="fas fa-users"></i> Users Management
      </a>
      <a href="<?= BASE_URL ?>admin/restaurants.php" class="sidebar-link <?= $currentPage === 'restaurants.php' ? 'active' : '' ?>">
        <i class="fas fa-store"></i> Restaurants
      </a>
      <a href="<?= BASE_URL ?>admin/add-restaurant.php" class="sidebar-link <?= $currentPage === 'add-restaurant.php' ? 'active' : '' ?>">
        <i class="fas fa-plus-circle"></i> Add Restaurant
      </a>
      <a href="<?= BASE_URL ?>admin/food-management.php" class="sidebar-link <?= $currentPage === 'food-management.php' ? 'active' : '' ?>">
        <i class="fas fa-utensils"></i> All Food Items
      </a>
      <a href="<?= BASE_URL ?>admin/orders.php" class="sidebar-link <?= $currentPage === 'orders.php' ? 'active' : '' ?>">
        <i class="fas fa-shopping-basket"></i> Platform Orders
      </a>
      <a href="<?= BASE_URL ?>admin/payments.php" class="sidebar-link <?= $currentPage === 'payments.php' ? 'active' : '' ?>">
        <i class="fas fa-wallet"></i> Financial Transactions
      </a>
    <?php endif; ?>

    <a href="<?= BASE_URL ?>logout.php" class="sidebar-link" style="margin-top: auto; color: #ef4444;">
      <i class="fas fa-sign-out-alt"></i> Sign Out
    </a>
  </nav>
</aside>
