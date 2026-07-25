<?php
$pageTitle = "Restaurant Profile";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Restaurant.php';

requireRole(ROLE_RESTAURANT);

$restaurantModel = new Restaurant();
$restaurant = $restaurantModel->getByUserId($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $cuisine = sanitize($_POST['cuisine'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $address = sanitize($_POST['address'] ?? '');
    $delivery_time = sanitize($_POST['delivery_time'] ?? '25-35 min');
    $delivery_fee = floatval($_POST['delivery_fee'] ?? 2.99);
    $min_order = floatval($_POST['min_order'] ?? 10.00);
    $description = sanitize($_POST['description'] ?? '');

    if ($restaurant) {
        $restaurantModel->update($restaurant['id'], $name, $cuisine, $phone, $address, $delivery_time, $delivery_fee, $min_order, $description);
    } else {
        $restaurantModel->create($_SESSION['user_id'], $name, $cuisine, $phone, $address, $delivery_time, $delivery_fee, $min_order, $description);
    }
    flash('success', 'Restaurant details updated successfully!', 'success');
    redirect(BASE_URL . 'restaurant/profile.php');
}
?>

<div class="dashboard-layout">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>

  <main class="dashboard-main">
    <div style="max-width: 650px;">
      <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--dark); margin-bottom: 1.5rem;">Store Profile &amp; Settings</h1>

      <div style="background: var(--white); padding: 2.5rem; border-radius: var(--radius-lg); border: 1px solid var(--gray-200); box-shadow: var(--shadow-sm);">
        <form action="" method="POST" data-validate="true">
          <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Restaurant Name</label>
            <input type="text" name="name" value="<?= sanitize($restaurant['name'] ?? '') ?>" required style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
          </div>

          <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Cuisine Specialty</label>
            <input type="text" name="cuisine" value="<?= sanitize($restaurant['cuisine'] ?? '') ?>" required placeholder="e.g. Italian &amp; Woodfired Pizza" style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
            <div>
              <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Phone</label>
              <input type="text" name="phone" value="<?= sanitize($restaurant['phone'] ?? '') ?>" style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
            </div>
            <div>
              <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Delivery Time Estimate</label>
              <input type="text" name="delivery_time" value="<?= sanitize($restaurant['delivery_time'] ?? '25-35 min') ?>" style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
            </div>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
            <div>
              <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Delivery Fee (<?= currSymbol() ?>)</label>
              <input type="number" step="0.01" name="delivery_fee" value="<?= $restaurant['delivery_fee'] ?? 2.99 ?>" style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
            </div>
            <div>
              <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Min Order Amount (<?= currSymbol() ?>)</label>
              <input type="number" step="0.01" name="min_order" value="<?= $restaurant['min_order'] ?? 10.00 ?>" style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
            </div>
          </div>

          <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Physical Address</label>
            <input type="text" name="address" value="<?= sanitize($restaurant['address'] ?? '') ?>" required style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
          </div>

          <div style="margin-bottom: 2rem;">
            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Description</label>
            <textarea name="description" rows="3" style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;"><?= sanitize($restaurant['description'] ?? '') ?></textarea>
          </div>

          <button type="submit" class="btn btn-primary" style="width: 100%;">Update Restaurant Profile</button>
        </form>
      </div>
    </div>
  </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
