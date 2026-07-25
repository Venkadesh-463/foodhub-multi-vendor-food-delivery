<?php
// Dynamic Navigation Bar
$currencies = getCurrencies();
$selectedCurr = $_SESSION['currency'] ?? 'USD';
$activeCurr = $currencies[$selectedCurr] ?? $currencies['USD'];
?>
<header class="header-nav">
  <div class="nav-container">
    <a href="<?= BASE_URL ?>index.php" class="brand-logo">
      <i class="fas fa-utensils"></i>
      <span>Food<span style="color: var(--primary);">Hub</span></span>
    </a>

    <nav class="nav-menu">
      <a href="<?= BASE_URL ?>index.php" class="nav-link"><i class="fas fa-home"></i> Home</a>
      <a href="<?= BASE_URL ?>user/restaurants.php" class="nav-link"><i class="fas fa-store"></i> Restaurants</a>
      <a href="<?= BASE_URL ?>about.php" class="nav-link"><i class="fas fa-info-circle"></i> About</a>
      <a href="<?= BASE_URL ?>contact.php" class="nav-link"><i class="fas fa-envelope"></i> Contact</a>
    </nav>

    <div class="nav-actions">

      <!-- 🌍 Currency Switcher Dropdown -->
      <div class="currency-switcher" id="currencySwitcher">
        <button class="currency-trigger" onclick="toggleCurrencyMenu()" title="Switch Currency">
          <span class="curr-flag"><?= $activeCurr['flag'] ?></span>
          <span class="curr-code"><?= $activeCurr['code'] ?></span>
          <i class="fas fa-chevron-down curr-chevron"></i>
        </button>
        <div class="currency-dropdown" id="currencyDropdown">
          <div class="curr-dropdown-header">
            <i class="fas fa-globe-americas"></i> Select Currency
          </div>
          <?php foreach ($currencies as $code => $c): ?>
            <a href="?set_curr=<?= $code ?>" 
               class="curr-option <?= $selectedCurr === $code ? 'curr-active' : '' ?>"
               onclick="sessionStorage.setItem('currency','<?= $code ?>')">
              <span class="curr-flag-sm"><?= $c['flag'] ?></span>
              <span class="curr-name"><?= $c['name'] ?></span>
              <?php if ($selectedCurr === $code): ?>
                <i class="fas fa-check curr-check"></i>
              <?php endif; ?>
            </a>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Cart Icon -->
      <a href="<?= BASE_URL ?>user/cart.php" class="cart-icon-btn" title="View Cart">
        <i class="fas fa-shopping-bag"></i>
        <span class="cart-badge"><?= $cartTotals['count'] ?? 0 ?></span>
      </a>

      <?php if ($authUser): ?>
        <div style="display: flex; align-items: center; gap: 0.75rem;">
          <a href="<?= 
            $authUser['role'] === ROLE_ADMIN ? BASE_URL . 'admin/dashboard.php' :
            ($authUser['role'] === ROLE_RESTAURANT ? BASE_URL . 'restaurant/dashboard.php' :
            ($authUser['role'] === ROLE_DELIVERY ? BASE_URL . 'delivery/dashboard.php' : BASE_URL . 'user/dashboard.php')) 
          ?>" class="btn btn-outline btn-sm">
            <i class="fas fa-user-circle"></i> <?= sanitize($authUser['name']) ?>
          </a>
          <a href="<?= BASE_URL ?>logout.php" class="btn btn-sm" style="background: var(--gray-100); color: var(--gray-800);" title="Logout">
            <i class="fas fa-sign-out-alt"></i>
          </a>
        </div>
      <?php else: ?>
        <a href="<?= BASE_URL ?>login.php" class="btn btn-outline btn-sm">Log In</a>
        <a href="<?= BASE_URL ?>register.php" class="btn btn-primary btn-sm">Sign Up</a>
      <?php endif; ?>
    </div>
  </div>
</header>

<script>
function toggleCurrencyMenu() {
  const dropdown = document.getElementById('currencyDropdown');
  const switcher = document.getElementById('currencySwitcher');
  dropdown.classList.toggle('open');
  switcher.classList.toggle('open');
}
// Close when clicking outside
document.addEventListener('click', function(e) {
  const sw = document.getElementById('currencySwitcher');
  if (sw && !sw.contains(e.target)) {
    document.getElementById('currencyDropdown').classList.remove('open');
    sw.classList.remove('open');
  }
});
</script>
