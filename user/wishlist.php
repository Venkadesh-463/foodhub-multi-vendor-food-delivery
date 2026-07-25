<?php
$pageTitle = "My Wishlist";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../models/FoodModel.php';

requireRole(ROLE_USER);

$foodModel = new FoodModel();
$wishlistItems = $foodModel->getWishlistItems($_SESSION['user_id']);
?>

<div class="dashboard-layout">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>

  <main class="dashboard-main">
    <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--dark); margin-bottom: 1.5rem;">My Favorite Dishes</h1>

    <div class="card-grid">
      <?php if (empty($wishlistItems)): ?>
        <p style="grid-column: 1 / -1; text-align: center; color: var(--gray-600); padding: 3rem;">Your wishlist is currently empty.</p>
      <?php else: ?>
        <?php foreach ($wishlistItems as $item): ?>
          <div class="food-card">
            <div class="card-img-wrapper">
              <img src="<?= getFoodImage($item['image']) ?>" alt="<?= sanitize($item['name']) ?>">
              <span class="badge-tag <?= $item['is_veg'] ? 'badge-veg' : 'badge-nonveg' ?>">
                <?= $item['is_veg'] ? 'Veg' : 'Non-Veg' ?>
              </span>
            </div>
            <div class="card-content">
              <h3 class="card-title"><?= sanitize($item['name']) ?></h3>
              <p class="card-desc"><?= sanitize($item['description']) ?></p>
              <div class="card-footer">
                <span class="food-price"><?= formatPrice($item['price']) ?></span>
                <button onclick="addToCart(<?= $item['id'] ?>)" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add to Cart</button>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
