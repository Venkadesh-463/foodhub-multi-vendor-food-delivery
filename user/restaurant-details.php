<?php
$pageTitle = "Restaurant Menu";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Restaurant.php';
require_once __DIR__ . '/../classes/Food.php';

$restaurantId = intval($_GET['id'] ?? 1);
$restaurantModel = new Restaurant();
$foodModel = new Food();

$restaurant = $restaurantModel->getById($restaurantId);
if (!$restaurant) {
    flash('error', 'Restaurant not found.', 'danger');
    redirect(BASE_URL . 'user/restaurants.php');
}

$foodItems = $foodModel->getAll($restaurantId);
?>

<!-- Restaurant Header Banner -->
<div style="background: linear-gradient(rgba(15, 23, 42, 0.7), rgba(15, 23, 42, 0.9)), url('<?= getRestaurantImage($restaurant['image']) ?>'); background-size: cover; background-position: center; color: var(--white); padding: 4rem 2rem;">
  <div style="max-width: 1280px; margin: 0 auto; display: flex; align-items: flex-end; justify-content: space-between; flex-wrap: wrap; gap: 2rem;">
    <div>
      <span style="background: var(--primary); padding: 0.3rem 0.8rem; border-radius: var(--radius-full); font-size: 0.8rem; font-weight: 700; text-transform: uppercase;">
        <?= sanitize($restaurant['cuisine']) ?>
      </span>
      <h1 style="font-size: 2.8rem; font-weight: 800; margin: 0.75rem 0 0.5rem 0;"><?= sanitize($restaurant['name']) ?></h1>
      <p style="color: var(--gray-400); font-size: 1rem;"><i class="fas fa-map-marker-alt" style="color: var(--primary);"></i> <?= sanitize($restaurant['address']) ?> &bull; <i class="fas fa-phone"></i> <?= sanitize($restaurant['phone']) ?></p>
    </div>

    <div style="display: flex; gap: 2rem; background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: 1rem 1.5rem; border-radius: var(--radius-md); border: 1px solid rgba(255,255,255,0.2);">
      <div>
        <div style="font-size: 1.25rem; font-weight: 800; color: #f59e0b;"><i class="fas fa-star"></i> <?= $restaurant['rating'] ?></div>
        <div style="font-size: 0.8rem; color: var(--gray-400);">Rating</div>
      </div>
      <div>
        <div style="font-size: 1.25rem; font-weight: 800; color: var(--white);"><i class="fas fa-clock"></i> <?= $restaurant['delivery_time'] ?></div>
        <div style="font-size: 0.8rem; color: var(--gray-400);">Delivery Time</div>
      </div>
      <div>
        <div style="font-size: 1.25rem; font-weight: 800; color: var(--white);"><?= formatPrice($restaurant['delivery_fee']) ?></div>
        <div style="font-size: 0.8rem; color: var(--gray-400);">Delivery Fee</div>
      </div>
    </div>
  </div>
</div>

<!-- Menu Section -->
<div class="section" style="padding-top: 3rem; padding-bottom: 5rem;">
  <div class="section-header">
    <div>
      <h2 class="section-title">Menu Items</h2>
      <p class="section-subtitle">Select dishes to add to your order</p>
    </div>
  </div>

  <div class="card-grid">
    <?php if (empty($foodItems)): ?>
      <p style="grid-column: 1 / -1; text-align: center; color: var(--gray-600); padding: 3rem;">No food items currently available for this restaurant.</p>
    <?php else: ?>
      <?php foreach ($foodItems as $food): ?>
        <div class="food-card">
          <div class="card-img-wrapper">
            <img src="<?= getFoodImage($food['image']) ?>" alt="<?= sanitize($food['name']) ?>">
            <span class="badge-tag <?= $food['is_veg'] ? 'badge-veg' : 'badge-nonveg' ?>">
              <?= $food['is_veg'] ? 'Veg' : 'Non-Veg' ?>
            </span>
          </div>
          <div class="card-content">
            <h3 class="card-title"><?= sanitize($food['name']) ?></h3>
            <p class="card-desc"><?= sanitize($food['description']) ?></p>
            <div class="card-footer">
              <span class="food-price"><?= formatPrice($food['price']) ?></span>
              <div style="display: flex; gap: 0.5rem;">
                <a href="<?= BASE_URL ?>user/food-details.php?id=<?= $food['id'] ?>" class="btn btn-outline btn-sm" title="View Details"><i class="fas fa-eye"></i></a>
                <button onclick="addToCart(<?= $food['id'] ?>)" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add</button>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
