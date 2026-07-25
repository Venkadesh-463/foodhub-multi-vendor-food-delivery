<?php
$pageTitle = "Shopping Cart";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Cart.php';

$cart = new Cart();
$userId = $_SESSION['user_id'] ?? null;
$totals = $cart->getTotals($userId);
$items = $totals['items'];
?>

<div class="section" style="padding-top: 3rem; padding-bottom: 5rem;">
  <h1 style="font-size: 2rem; font-weight: 800; color: var(--dark); margin-bottom: 2rem;">Your Cart</h1>

  <?php if (empty($items)): ?>
    <div style="text-align: center; background: var(--white); padding: 4rem 2rem; border-radius: var(--radius-lg); border: 1px solid var(--gray-200);">
      <i class="fas fa-shopping-basket" style="font-size: 3.5rem; color: var(--gray-400); margin-bottom: 1rem;"></i>
      <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--dark); margin-bottom: 0.5rem;">Your cart is currently empty</h3>
      <p style="color: var(--gray-600); margin-bottom: 1.5rem;">Looks like you haven't added any delicious food items to your cart yet.</p>
      <a href="<?= BASE_URL ?>user/restaurants.php" class="btn btn-primary">Browse Restaurants</a>
    </div>
  <?php else: ?>
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2.5rem;">
      <div>
        <div class="table-container">
          <table class="custom-table">
            <thead>
              <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($items as $item): ?>
                <tr>
                  <td style="display: flex; align-items: center; gap: 1rem;">
                    <img src="<?= getFoodImage($item['image']) ?>" style="width: 50px; height: 50px; border-radius: var(--radius-sm); object-fit: cover;">
                    <div>
                      <strong style="display: block; font-size: 0.95rem;"><?= sanitize($item['name']) ?></strong>
                      <span style="font-size: 0.8rem; color: var(--gray-600);"><?= sanitize($item['restaurant_name']) ?></span>
                    </div>
                  </td>
                  <td><strong><?= formatPrice($item['price']) ?></strong></td>
                  <td>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                      <button onclick="updateCartQty(<?= $item['id'] ?>, <?= $item['quantity'] - 1 ?>)" class="btn btn-sm" style="background: var(--gray-100); padding: 0.2rem 0.6rem;">-</button>
                      <span style="font-weight: 700; font-size: 0.95rem; min-width: 20px; text-align: center;"><?= $item['quantity'] ?></span>
                      <button onclick="updateCartQty(<?= $item['id'] ?>, <?= $item['quantity'] + 1 ?>)" class="btn btn-sm" style="background: var(--gray-100); padding: 0.2rem 0.6rem;">+</button>
                    </div>
                  </td>
                  <td><strong><?= formatPrice($item['price'] * $item['quantity']) ?></strong></td>
                  <td>
                    <button onclick="removeFromCart(<?= $item['id'] ?>)" style="background: none; border: none; color: var(--danger); cursor: pointer; font-size: 1.1rem;">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Order Summary Card -->
      <div style="background: var(--white); padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--gray-200); box-shadow: var(--shadow-sm); height: fit-content;">
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--dark); margin-bottom: 1.5rem; border-bottom: 1px solid var(--gray-200); padding-bottom: 0.75rem;">Summary</h3>
        
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.95rem; color: var(--gray-600);">
          <span>Items Subtotal</span>
          <span><?= formatPrice($totals['subtotal']) ?></span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.95rem; color: var(--gray-600);">
          <span>Delivery Fee</span>
          <span><?= formatPrice($totals['delivery_fee']) ?></span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem; font-size: 0.95rem; color: var(--gray-600);">
          <span>Estimated Tax (8%)</span>
          <span><?= formatPrice($totals['tax']) ?></span>
        </div>

        <div style="display: flex; justify-content: space-between; font-size: 1.3rem; font-weight: 800; color: var(--dark); border-top: 2px solid var(--gray-200); padding-top: 1rem; margin-bottom: 2rem;">
          <span>Grand Total</span>
          <span style="color: var(--primary);"><?= formatPrice($totals['grand_total']) ?></span>
        </div>

        <a href="<?= BASE_URL ?>user/checkout.php" class="btn btn-primary" style="width: 100%; padding: 1rem;">
          Proceed to Checkout <i class="fas fa-arrow-right"></i>
        </a>
      </div>
    </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
