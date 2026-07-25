<?php
$pageTitle = "Food Details";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Food.php';

$foodId = intval($_GET['id'] ?? 1);
$foodModel = new Food();
$food = $foodModel->getById($foodId);

if (!$food) {
    flash('error', 'Food item not found.', 'danger');
    redirect(BASE_URL . 'user/restaurants.php');
}
?>

<div class="section" style="padding-top: 3rem; padding-bottom: 5rem;">
  <div style="background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--gray-200); box-shadow: var(--shadow-md); overflow: hidden; display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
    <div style="height: 450px; background: var(--gray-100);">
      <img src="<?= getFoodImage($food['image']) ?>" alt="<?= sanitize($food['name']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
    </div>

    <div style="padding: 3rem 2.5rem 3rem 1rem; display: flex; flex-direction: column; justify-content: center;">
      <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem;">
        <span class="badge-tag <?= $food['is_veg'] ? 'badge-veg' : 'badge-nonveg' ?>" style="position: static;">
          <?= $food['is_veg'] ? 'Pure Vegetarian' : 'Non-Vegetarian' ?>
        </span>
        <span style="background: var(--primary-light); color: var(--primary); padding: 0.35rem 0.75rem; border-radius: var(--radius-full); font-size: 0.75rem; font-weight: 700;">
          <?= sanitize($food['category_name']) ?>
        </span>
      </div>

      <h1 style="font-size: 2.2rem; font-weight: 800; color: var(--dark); margin-bottom: 0.5rem;"><?= sanitize($food['name']) ?></h1>
      <p style="color: var(--gray-600); margin-bottom: 1.5rem; font-size: 1rem; line-height: 1.7;"><?= sanitize($food['description']) ?></p>

      <div style="margin-bottom: 1.5rem; font-size: 0.95rem; color: var(--gray-600);">
        <i class="fas fa-store" style="color: var(--primary);"></i> Prepared by <strong><?= sanitize($food['restaurant_name']) ?></strong>
      </div>

      <div style="display: flex; align-items: center; gap: 2rem; margin-bottom: 2rem;">
        <div class="food-price" style="font-size: 2rem;"><?= formatPrice($food['price']) ?></div>
      </div>

      <div style="display: flex; gap: 1rem;">
        <button onclick="addToCart(<?= $food['id'] ?>)" class="btn btn-primary" style="flex: 1; padding: 1rem;">
          <i class="fas fa-shopping-bag"></i> Add to Cart
        </button>
        <button onclick="toggleWishlist(<?= $food['id'] ?>, this)" class="btn btn-outline" style="padding: 1rem;">
          <i class="fas fa-heart"></i>
        </button>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
