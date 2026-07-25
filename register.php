<?php
$pageTitle = "Create Account";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/controllers/AuthController.php';

$auth = new AuthController();
$auth->handleRegister();
?>

<div class="section" style="padding-top: 3rem; padding-bottom: 5rem; max-width: 540px;">
  <div style="background: var(--white); padding: 3rem 2.5rem; border-radius: var(--radius-lg); border: 1px solid var(--gray-200); box-shadow: var(--shadow-lg);">
    <div style="text-align: center; margin-bottom: 2rem;">
      <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--dark);">Create Your Account</h1>
      <p style="color: var(--gray-600); font-size: 0.9rem;">Join thousands of food lovers & culinary partners</p>
    </div>

    <form action="" method="POST" data-validate="true">
      <div style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">I want to join as</label>
        <select name="role" required style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
          <option value="user">Customer / Food Lover</option>
          <option value="restaurant">Restaurant Partner</option>
          <option value="delivery">Delivery Driver</option>
        </select>
      </div>

      <div style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Full Name</label>
        <input type="text" name="name" required placeholder="John Doe" style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
      </div>

      <div style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Email Address</label>
        <input type="email" name="email" required placeholder="name@example.com" style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
        <div>
          <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Phone Number</label>
          <input type="text" name="phone" placeholder="+1 555 000 0000" style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
        </div>
        <div>
          <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Delivery Address</label>
          <input type="text" name="address" placeholder="123 Main St" style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
        </div>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
        <div>
          <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Password</label>
          <input type="password" name="password" required placeholder="••••••••" style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
        </div>
        <div>
          <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Confirm Password</label>
          <input type="password" name="confirm_password" required placeholder="••••••••" style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
        </div>
      </div>

      <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.9rem;">
        Register Now <i class="fas fa-user-plus"></i>
      </button>
    </form>

    <p style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem; color: var(--gray-600);">
      Already have an account? <a href="<?= BASE_URL ?>login.php" style="color: var(--primary); font-weight: 700;">Sign In</a>
    </p>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
