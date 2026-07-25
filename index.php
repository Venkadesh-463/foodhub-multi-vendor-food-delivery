<?php
$pageTitle = "Home - Delicious Food Delivered Fast";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/classes/Restaurant.php';
require_once __DIR__ . '/classes/Food.php';

$restaurantModel = new Restaurant();
$foodModel = new Food();

$restaurants = $restaurantModel->getAll('approved');
$categories = $foodModel->getCategories();
$featuredItems = $foodModel->getAll(null, null, true);
?>

<!-- Hero Banner Section -->
<section class="hero-section">
  <div class="hero-container">
    <div>
      <h1 class="hero-title">Hungry? We've got <span>your favorite food</span> covered.</h1>
      <p class="hero-subtitle">Order from the finest gourmet kitchens and local favorite bistros near you. Fast, reliable delivery straight to your doorstep.</p>

      <form action="<?= BASE_URL ?>user/restaurants.php" method="GET" class="hero-search-box">
        <i class="fas fa-search" style="color: var(--gray-400); margin-right: 0.75rem;"></i>
        <input type="text" name="search" placeholder="Search for dishes, restaurants, or cuisines...">
        <button type="submit" class="btn btn-primary" style="border-radius: var(--radius-full);">Find Food</button>
      </form>
    </div>
    <div style="text-align: center;">
      <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=800&q=80" alt="Delicious Feast" style="width: 100%; max-width: 500px; border-radius: var(--radius-lg); box-shadow: 0 25px 50px rgba(0,0,0,0.5);">
    </div>
  </div>
</section>

<!-- Categories Section -->
<section class="section">
  <div class="section-header">
    <div>
      <h2 class="section-title">Explore Categories</h2>
      <p class="section-subtitle">What are you in the mood for today?</p>
    </div>
  </div>

  <div class="category-grid">
    <?php foreach ($categories as $cat): ?>
      <a href="<?= BASE_URL ?>user/restaurants.php?category=<?= $cat['id'] ?>" class="category-card">
        <div class="category-icon">
          <i class="fas <?= $cat['icon'] ?>"></i>
        </div>
        <div class="category-name"><?= sanitize($cat['name']) ?></div>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<!-- Featured Food Items -->
<section class="section" style="background: var(--white); border-radius: var(--radius-lg); margin-bottom: 3rem; padding-top: 3rem; padding-bottom: 3rem;">
  <div class="section-header">
    <div>
      <h2 class="section-title">Trending Dishes</h2>
      <p class="section-subtitle">Handpicked culinary masterpieces loved by our foodies</p>
    </div>
  </div>

  <div class="card-grid">
    <?php foreach ($featuredItems as $item): ?>
      <div class="food-card">
        <div class="card-img-wrapper">
          <img src="<?= getFoodImage($item['image']) ?>" alt="<?= sanitize($item['name']) ?>">
          <span class="badge-tag <?= $item['is_veg'] ? 'badge-veg' : 'badge-nonveg' ?>">
            <?= $item['is_veg'] ? 'Veg' : 'Non-Veg' ?>
          </span>
          <span class="badge-rating"><i class="fas fa-star" style="color: #f59e0b;"></i> 4.9</span>
        </div>
        <div class="card-content">
          <h3 class="card-title"><?= sanitize($item['name']) ?></h3>
          <p class="card-desc"><?= sanitize($item['description']) ?></p>
          <div class="card-footer">
            <span class="food-price"><?= formatPrice($item['price']) ?></span>
            <button onclick="addToCart(<?= $item['id'] ?>)" class="btn btn-primary btn-sm">
              <i class="fas fa-plus"></i> Add to Cart
            </button>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Top Restaurants Section -->
<section class="section" style="padding-bottom: 5rem;">
  <div class="section-header">
    <div>
      <h2 class="section-title">Featured Restaurants</h2>
      <p class="section-subtitle">Top rated dining establishments partnering with FoodHub</p>
    </div>
    <a href="<?= BASE_URL ?>user/restaurants.php" class="btn btn-outline">View All Restaurants <i class="fas fa-arrow-right"></i></a>
  </div>

  <div class="card-grid">
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
            <a href="<?= BASE_URL ?>user/restaurant-details.php?id=<?= $rest['id'] ?>" class="btn btn-outline btn-sm">View Menu</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
