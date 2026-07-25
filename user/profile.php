<?php
$pageTitle = "User Profile";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../controllers/UserController.php';

requireRole(['user', 'restaurant', 'delivery', 'admin']);

$userObj = new User();
$user = $userObj->getUserById($_SESSION['user_id']);

$controller = new UserController();
$controller->handleProfileUpdate();
?>

<div class="dashboard-layout">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>

  <main class="dashboard-main">
    <div style="max-width: 650px;">
      <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--dark); margin-bottom: 1.5rem;">Account Profile</h1>

      <div style="background: var(--white); padding: 2.5rem; border-radius: var(--radius-lg); border: 1px solid var(--gray-200); box-shadow: var(--shadow-sm);">
        <form action="" method="POST" enctype="multipart/form-data" data-validate="true">
          <div style="margin-bottom: 1.5rem; text-align: center;">
            <div style="width: 100px; height: 100px; border-radius: var(--radius-full); background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 1rem auto; overflow: hidden;">
              <?php if (!empty($user['avatar']) && file_exists(ROOT_PATH . 'uploads/profile/' . $user['avatar'])): ?>
                <img src="<?= BASE_URL ?>uploads/profile/<?= $user['avatar'] ?>" style="width: 100%; height: 100%; object-fit: cover;">
              <?php else: ?>
                <i class="fas fa-user"></i>
              <?php endif; ?>
            </div>
            <input type="file" name="avatar" accept="image/*" style="font-size: 0.85rem;">
          </div>

          <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Full Name</label>
            <input type="text" name="name" value="<?= sanitize($user['name']) ?>" required style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
          </div>

          <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Email Address (Read Only)</label>
            <input type="email" value="<?= sanitize($user['email']) ?>" readonly style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); background: var(--gray-100); outline: none;">
          </div>

          <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Phone Number</label>
            <input type="text" name="phone" value="<?= sanitize($user['phone'] ?? '') ?>" placeholder="+1 555 000 0000" style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
          </div>

          <div style="margin-bottom: 2rem;">
            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Default Delivery Address</label>
            <textarea name="address" rows="3" style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;"><?= sanitize($user['address'] ?? '') ?></textarea>
          </div>

          <button type="submit" class="btn btn-primary" style="width: 100%;">Save Changes</button>
        </form>
      </div>
    </div>
  </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
