<?php
$pageTitle = "Login";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/controllers/AuthController.php';

if (isLoggedIn()) {
    $role = $_SESSION['user_role'] ?? 'user';
    if ($role === ROLE_ADMIN) redirect(BASE_URL . 'admin/dashboard.php');
    elseif ($role === ROLE_RESTAURANT) redirect(BASE_URL . 'restaurant/dashboard.php');
    elseif ($role === ROLE_DELIVERY) redirect(BASE_URL . 'delivery/dashboard.php');
    else redirect(BASE_URL . 'user/dashboard.php');
}

$auth = new AuthController();
$auth->handleLogin();
?>

<div class="section" style="padding-top: 4rem; padding-bottom: 6rem; max-width: 480px;">
  <div style="background: var(--white); padding: 3rem 2.5rem; border-radius: var(--radius-lg); border: 1px solid var(--gray-200); box-shadow: var(--shadow-lg);">
    <div style="text-align: center; margin-bottom: 2rem;">
      <div style="width: 60px; height: 60px; background: var(--primary-light); color: var(--primary); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin: 0 auto 1rem auto;">
        <i class="fas fa-utensils"></i>
      </div>
      <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--dark);">Welcome Back</h1>
      <p style="color: var(--gray-600); font-size: 0.9rem;">Sign in to your FoodHub account</p>
    </div>

    <form action="" method="POST" data-validate="true">
      <div style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Email Address</label>
        <input type="email" name="email" required placeholder="name@example.com" style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
      </div>
      <div style="margin-bottom: 1.5rem;">
        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Password</label>
        <input type="password" name="password" required placeholder="••••••••" style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
      </div>

      <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.9rem;">
        Sign In <i class="fas fa-arrow-right"></i>
      </button>
    </form>

    <!-- Quick Demo Logins Helper -->
    <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px dashed var(--gray-200); font-size: 0.85rem;">
      <p style="font-weight: 700; color: var(--dark); margin-bottom: 0.5rem;">Demo Quick Credentials (Password: <code>password123</code>):</p>
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
        <button type="button" onclick="fillLogin('user@foodhub.com')" class="btn btn-sm" style="background: var(--gray-100);">Customer Demo</button>
        <button type="button" onclick="fillLogin('restaurant@foodhub.com')" class="btn btn-sm" style="background: var(--gray-100);">Restaurant Demo</button>
        <button type="button" onclick="fillLogin('delivery@foodhub.com')" class="btn btn-sm" style="background: var(--gray-100);">Driver Demo</button>
        <button type="button" onclick="fillLogin('admin@foodhub.com')" class="btn btn-sm" style="background: var(--gray-100);">Admin Demo</button>
      </div>
    </div>

    <p style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem; color: var(--gray-600);">
      Don't have an account? <a href="<?= BASE_URL ?>register.php" style="color: var(--primary); font-weight: 700;">Create One</a>
    </p>
  </div>
</div>

<script>
function fillLogin(email) {
    document.querySelector('input[name="email"]').value = email;
    document.querySelector('input[name="password"]').value = 'password123';
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
