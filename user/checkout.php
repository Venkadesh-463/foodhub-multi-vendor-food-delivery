<?php
$pageTitle = "Checkout";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Cart.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../controllers/OrderController.php';

requireRole(ROLE_USER);

$cart = new Cart();
$totals = $cart->getTotals($_SESSION['user_id']);

if ($totals['count'] === 0) {
    flash('error', 'Your cart is empty.', 'danger');
    redirect(BASE_URL . 'user/restaurants.php');
}

$userObj = new User();
$user = $userObj->getUserById($_SESSION['user_id']);

$orderController = new OrderController();
$orderController->handleCheckout();
?>

<div class="section" style="padding-top: 3rem; padding-bottom: 5rem;">
  <h1 style="font-size: 2rem; font-weight: 800; color: var(--dark); margin-bottom: 2rem;">Checkout</h1>

  <form action="" method="POST" data-validate="true">
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2.5rem;">
      <div style="display: flex; flex-direction: column; gap: 2rem;">
        
        <!-- Delivery Address Box -->
        <div style="background: var(--white); padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--gray-200); box-shadow: var(--shadow-sm);">
          <h3 style="font-size: 1.2rem; font-weight: 700; color: var(--dark); margin-bottom: 1rem;"><i class="fas fa-map-marker-alt" style="color: var(--primary);"></i> Delivery Address</h3>
          <textarea name="address" rows="3" required style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;"><?= sanitize($user['address'] ?? '') ?></textarea>
        </div>

        <!-- Payment Method Options -->
        <div style="background: var(--white); padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--gray-200); box-shadow: var(--shadow-sm);">
          <h3 style="font-size: 1.2rem; font-weight: 700; color: var(--dark); margin-bottom: 1.25rem;"><i class="fas fa-credit-card" style="color: var(--primary);"></i> Payment Method</h3>
          
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem;">
            <label style="border: 2px solid var(--primary); padding: 1rem; border-radius: var(--radius-md); display: flex; align-items: center; gap: 0.75rem; cursor: pointer; background: var(--primary-light);">
              <input type="radio" name="payment_method" value="card" checked>
              <span><i class="fas fa-credit-card"></i> Credit / Debit Card</span>
            </label>

            <label style="border: 1px solid var(--gray-200); padding: 1rem; border-radius: var(--radius-md); display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
              <input type="radio" name="payment_method" value="upi">
              <span><i class="fas fa-mobile-alt"></i> Instant UPI / Wallet</span>
            </label>

            <label style="border: 1px solid var(--gray-200); padding: 1rem; border-radius: var(--radius-md); display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
              <input type="radio" name="payment_method" value="cod">
              <span><i class="fas fa-money-bill-wave"></i> Cash on Delivery</span>
            </label>
          </div>
        </div>

        <!-- Special Delivery Instructions -->
        <div style="background: var(--white); padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--gray-200); box-shadow: var(--shadow-sm);">
          <h3 style="font-size: 1.2rem; font-weight: 700; color: var(--dark); margin-bottom: 1rem;"><i class="fas fa-comment-alt" style="color: var(--primary);"></i> Special Instructions</h3>
          <input type="text" name="instructions" placeholder="e.g. Leave with gate security, extra spicy sauce, etc." style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
        </div>

      </div>

      <!-- Total Price Card -->
      <div style="background: var(--white); padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--gray-200); box-shadow: var(--shadow-sm); height: fit-content;">
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--dark); margin-bottom: 1.5rem; border-bottom: 1px solid var(--gray-200); padding-bottom: 0.75rem;">Payment Summary</h3>
        
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; color: var(--gray-600);">
          <span>Subtotal (<?= $totals['count'] ?> items)</span>
          <span><?= formatPrice($totals['subtotal']) ?></span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; color: var(--gray-600);">
          <span>Delivery Fee</span>
          <span><?= formatPrice($totals['delivery_fee']) ?></span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem; color: var(--gray-600);">
          <span>Taxes</span>
          <span><?= formatPrice($totals['tax']) ?></span>
        </div>

        <div style="display: flex; justify-content: space-between; font-size: 1.3rem; font-weight: 800; color: var(--dark); border-top: 2px solid var(--gray-200); padding-top: 1rem; margin-bottom: 2rem;">
          <span>Total Payable</span>
          <span style="color: var(--primary);"><?= formatPrice($totals['grand_total']) ?></span>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1.05rem;">
          Place Order <i class="fas fa-check-circle"></i>
        </button>
      </div>
    </div>
  </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
