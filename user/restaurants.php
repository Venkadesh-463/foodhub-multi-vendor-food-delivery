<?php
$pageTitle = "Browse Restaurants";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Restaurant.php';

$restaurantModel = new Restaurant();
$search = sanitize($_GET['search'] ?? '');
$cuisine = sanitize($_GET['cuisine'] ?? '');

$restaurants = $restaurantModel->getAll('approved', $cuisine, $search);
?>

<div class="section" style="padding-top: 3rem; padding-bottom: 5rem;">
  <div class="section-header">
    <div>
      <h1 class="section-title">All Partner Restaurants</h1>
      <p class="section-subtitle">Discover popular eateries around your area</p>
    </div>

    <!-- Search & Cuisine Filter -->
    <form action="" method="GET" style="display: flex; gap: 1rem;">
      <input type="text" name="search" value="<?= $search ?>" placeholder="Search restaurant name..." style="padding: 0.65rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
      <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Search</button>
    </form>
  </div>

  <div class="card-grid">
    <?php if (empty($restaurants)): ?>
      <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem;">
        <i class="fas fa-search" style="font-size: 3rem; color: var(--gray-400); margin-bottom: 1rem;"></i>
        <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">No restaurants found</h3>
        <p style="color: var(--gray-600);">Try searching for a different keyword or browse all restaurants.</p>
      </div>
    <?php else: ?>
      <?php foreach ($restaurants as $rest): ?>
        <div class="restaurant-card">
          <div class="card-img-wrapper">
            <img src="<?= getRestaurantImage($rest['image']) ?>" alt="<?= sanitize($rest['name']) ?>">
            <span class="badge-rating"><i class="fas fa-star" style="color: #f59e0b;"></i> <?= $rest['rating'] ?></span>
          </div>
          <div class="card-content">
            <h3 class="card-title"><?= sanitize($rest['name']) ?></h3>
            <p class="card-desc"><?= sanitize($rest['cuisine']) ?> &bull; <?= sanitize($rest['address']) ?></p>
            <div class="card-footer">
              <span style="font-size: 0.85rem; color: var(--gray-600);"><i class="fas fa-clock" style="color: var(--primary);"></i> <?= $rest['delivery_time'] ?></span>
              <a href="<?= BASE_URL ?>user/restaurant-details.php?id=<?= $rest['id'] ?>" class="btn btn-primary btn-sm">Explore Menu</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
