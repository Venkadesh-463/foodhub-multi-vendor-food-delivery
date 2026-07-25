<?php
$pageTitle = "User Management";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/User.php';

requireRole(ROLE_ADMIN);

$userModel = new User();

if (isset($_GET['status']) && isset($_GET['id'])) {
    $userModel->updateStatus(intval($_GET['id']), sanitize($_GET['status']));
    flash('success', 'User status updated successfully.', 'success');
    redirect(BASE_URL . 'admin/users.php');
}

$allUsers = $userModel->getAllUsers();
?>

<div class="dashboard-layout">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>

  <main class="dashboard-main">
    <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--dark); margin-bottom: 1.5rem;">Registered Users & Role Management</h1>

    <div class="table-container">
      <table class="custom-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($allUsers as $u): ?>
            <tr>
              <td>#<?= $u['id'] ?></td>
              <td><strong><?= sanitize($u['name']) ?></strong></td>
              <td><?= sanitize($u['email']) ?></td>
              <td><?= sanitize($u['phone'] ?? 'N/A') ?></td>
              <td><span style="font-weight: 700; text-transform: uppercase; font-size: 0.8rem; padding: 0.2rem 0.5rem; background: var(--gray-100); border-radius: var(--radius-sm);"><?= $u['role'] ?></span></td>
              <td><span class="status-badge status-<?= $u['status'] ?>"><?= ucfirst($u['status']) ?></span></td>
              <td>
                <?php if ($u['status'] === 'active'): ?>
                  <a href="<?= BASE_URL ?>admin/users.php?id=<?= $u['id'] ?>&status=suspended" class="btn btn-sm" style="background: #fee2e2; color: #991b1b;">Suspend Account</a>
                <?php else: ?>
                  <a href="<?= BASE_URL ?>admin/users.php?id=<?= $u['id'] ?>&status=active" class="btn btn-sm" style="background: #dcfce7; color: #166534;">Activate Account</a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
