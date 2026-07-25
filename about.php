<?php
$pageTitle = "About Us";
require_once __DIR__ . '/includes/header.php';
?>

<div class="section" style="padding-top: 3rem; padding-bottom: 5rem;">
  <div style="text-align: center; max-width: 800px; margin: 0 auto 4rem auto;">
    <h1 style="font-size: 2.8rem; font-weight: 800; color: var(--dark); margin-bottom: 1rem;">Crafting Exceptional Culinary Connections</h1>
    <p style="font-size: 1.15rem; color: var(--gray-600);">FoodHub was built with a simple mission: to connect passionate food lovers with the finest local chefs, restaurants, and bakeries through a seamless, lightning-fast delivery experience.</p>
  </div>

  <div class="stats-grid" style="margin-bottom: 4rem;">
    <div class="stat-card" style="justify-content: center; text-align: center; flex-direction: column;">
      <div class="stat-value" style="font-size: 2.5rem; color: var(--primary);">500+</div>
      <div class="stat-label">Partner Restaurants</div>
    </div>
    <div class="stat-card" style="justify-content: center; text-align: center; flex-direction: column;">
      <div class="stat-value" style="font-size: 2.5rem; color: var(--primary);">250,000+</div>
      <div class="stat-label">Happy Foodies</div>
    </div>
    <div class="stat-card" style="justify-content: center; text-align: center; flex-direction: column;">
      <div class="stat-value" style="font-size: 2.5rem; color: var(--primary);">22 Mins</div>
      <div class="stat-label">Average Delivery Time</div>
    </div>
    <div class="stat-card" style="justify-content: center; text-align: center; flex-direction: column;">
      <div class="stat-value" style="font-size: 2.5rem; color: var(--primary);">4.9 ★</div>
      <div class="stat-label">Customer Satisfaction</div>
    </div>
  </div>

  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center;">
    <div>
      <h2 style="font-size: 2rem; font-weight: 800; color: var(--dark); margin-bottom: 1rem;">Our Mission & Core Values</h2>
      <p style="color: var(--gray-600); margin-bottom: 1.5rem; line-height: 1.8;">We believe that great food brings people together. That's why we partner exclusively with verified kitchen partners who share our commitment to fresh ingredients, strict hygiene, and authentic flavors.</p>
      <ul style="display: flex; flex-direction: column; gap: 1rem;">
        <li style="display: flex; align-items: center; gap: 0.75rem; font-weight: 600;">
          <i class="fas fa-check-circle" style="color: var(--success); font-size: 1.2rem;"></i> Temperature controlled food safety delivery packaging
        </li>
        <li style="display: flex; align-items: center; gap: 0.75rem; font-weight: 600;">
          <i class="fas fa-check-circle" style="color: var(--success); font-size: 1.2rem;"></i> Real-time GPS tracking from kitchen stove to your doorstep
        </li>
        <li style="display: flex; align-items: center; gap: 0.75rem; font-weight: 600;">
          <i class="fas fa-check-circle" style="color: var(--success); font-size: 1.2rem;"></i> Dedicated 24/7 customer resolution & driver safety hotline
        </li>
      </ul>
    </div>
    <div>
      <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=800&q=80" alt="Restaurant Chef" style="width: 100%; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg);">
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
