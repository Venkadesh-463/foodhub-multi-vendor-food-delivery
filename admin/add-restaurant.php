<?php
$pageTitle = "Add New Restaurant";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Restaurant.php';

requireRole(ROLE_ADMIN);

$userModel = new User();
$restaurantModel = new Restaurant();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ownerName = sanitize($_POST['owner_name'] ?? '');
    $ownerEmail = filter_var($_POST['owner_email'] ?? '', FILTER_SANITIZE_EMAIL);
    $ownerPhone = sanitize($_POST['owner_phone'] ?? '');
    $ownerPassword = $_POST['owner_password'] ?? 'password123';

    $name = sanitize($_POST['name'] ?? '');
    $cuisine = sanitize($_POST['cuisine'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $address = sanitize($_POST['address'] ?? '');
    $deliveryTime = sanitize($_POST['delivery_time'] ?? '25-35 min');
    $deliveryFee = floatval($_POST['delivery_fee'] ?? 2.99);
    $minOrder = floatval($_POST['min_order'] ?? 10.00);
    $description = sanitize($_POST['description'] ?? '');

    if (empty($name) || empty($cuisine) || empty($address) || empty($ownerEmail)) {
        flash('error', 'Please fill in all required fields.', 'danger');
    } else {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$ownerEmail]);
        $existingUser = $stmt->fetch();

        if ($existingUser) {
            $userId = $existingUser['id'];
        } else {
            $regRes = $userModel->register($ownerName, $ownerEmail, $ownerPassword, $ownerPhone, $address, 'restaurant');
            if ($regRes['success']) {
                $userId = $regRes['user_id'];
            } else {
                flash('error', $regRes['message'], 'danger');
                redirect(BASE_URL . 'admin/add-restaurant.php');
            }
        }

        $imageName = 'default-restaurant.jpg';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmp = $_FILES['image']['tmp_name'];
            $imageName = time() . '_' . basename($_FILES['image']['name']);
            $uploadDir = ROOT_PATH . 'uploads/restaurant/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            move_uploaded_file($fileTmp, $uploadDir . $imageName);
        }

        $res = $restaurantModel->create($userId, $name, $cuisine, $phone, $address, $deliveryTime, $deliveryFee, $minOrder, $description, $imageName);

        if ($res) {
            flash('success', "Restaurant '$name' added successfully!", 'success');
            redirect(BASE_URL . 'admin/restaurants.php');
        } else {
            flash('error', 'Failed to create restaurant.', 'danger');
        }
    }
}
?>

<div class="dashboard-layout">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>

  <main class="dashboard-main">
    <div style="max-width: 750px;">
      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
        <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--dark);">Add New Restaurant</h1>
        <a href="<?= BASE_URL ?>admin/restaurants.php" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Back to Restaurants</a>
      </div>

      <div style="background: var(--white); padding: 2.5rem; border-radius: var(--radius-lg); border: 1px solid var(--gray-200); box-shadow: var(--shadow-sm);">
        <form action="" method="POST" enctype="multipart/form-data" data-validate="true">

          <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--primary); margin-bottom: 1rem; border-bottom: 1px solid var(--gray-100); padding-bottom: 0.5rem;">
            <i class="fas fa-store-alt"></i> Restaurant Information
          </h3>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
            <div>
              <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Restaurant Name *</label>
              <input type="text" name="name" required placeholder="e.g. Gourmet Burger Lounge" style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
            </div>
            <div>
              <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Cuisine Type *</label>
              <input type="text" name="cuisine" required placeholder="e.g. American &amp; Burgers" style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
            </div>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
            <div>
              <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Phone Number</label>
              <input type="text" name="phone" placeholder="+1 555 123 4567" style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
            </div>
            <div>
              <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Estimated Delivery Time</label>
              <input type="text" name="delivery_time" value="25-35 min" style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
            </div>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
            <div>
              <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Delivery Fee (<?= currSymbol() ?>)</label>
              <input type="number" step="0.01" name="delivery_fee" value="2.99" style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
            </div>
            <div>
              <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Minimum Order (<?= currSymbol() ?>)</label>
              <input type="number" step="0.01" name="min_order" value="10.00" style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
            </div>
          </div>

          <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Full Address *</label>
            <input type="text" name="address" required placeholder="123 Gourmet St, Downtown" style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
          </div>

          <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Description</label>
            <textarea name="description" rows="3" placeholder="Brief overview of the restaurant and specialties..." style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;"></textarea>
          </div>

          <div style="margin-bottom: 2rem;">
            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Banner / Logo Image</label>
            <input type="file" name="image" accept="image/*">
          </div>

          <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--primary); margin-bottom: 1rem; border-bottom: 1px solid var(--gray-100); padding-bottom: 0.5rem;">
            <i class="fas fa-user-shield"></i> Restaurant Owner Credentials
          </h3>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
            <div>
              <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Owner Name *</label>
              <input type="text" name="owner_name" required placeholder="Owner Full Name" style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
            </div>
            <div>
              <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Owner Email *</label>
              <input type="email" name="owner_email" required placeholder="owner@restaurant.com" style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
            </div>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 2rem;">
            <div>
              <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Owner Phone</label>
              <input type="text" name="owner_phone" placeholder="+1 555 999 8888" style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
            </div>
            <div>
              <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Owner Password</label>
              <input type="password" name="owner_password" value="password123" placeholder="Default: password123" style="width: 100%; padding: 0.85rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
            </div>
          </div>

          <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1.05rem;">
            <i class="fas fa-plus-circle"></i> Register &amp; Create Restaurant
          </button>

        </form>
      </div>
    </div>
  </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
