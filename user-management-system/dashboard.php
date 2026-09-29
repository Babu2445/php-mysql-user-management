<?php
require_once __DIR__ . '/includes/bootstrap.php';
requireLogin();
$user = currentUser($pdo);
$total = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$stmt = $pdo->query('SELECT id, name, email, created_at FROM users ORDER BY created_at DESC, id DESC LIMIT 5');
$recentUsers = $stmt->fetchAll();
$pageTitle = 'Overview'; $activePage = 'dashboard';
require __DIR__ . '/includes/header.php';
?>
<div class="page-heading"><div><div class="eyebrow">YOUR WORKSPACE</div><h1>Good to see you, <?= e($user['name'] ?? 'there') ?></h1><p>Here’s what’s happening with your user directory.</p></div><a class="btn btn-primary" href="create_user.php"><span>＋</span> Add user</a></div>
<div class="row g-4 mb-4"><div class="col-md-6 col-xl-4"><div class="stat-card"><div class="stat-icon indigo">♧</div><div class="stat-label">TOTAL USERS</div><div class="stat-number"><?= number_format($total) ?></div><div class="stat-note">Registered in your workspace</div></div></div><div class="col-md-6 col-xl-4"><div class="stat-card"><div class="stat-icon mint">✓</div><div class="stat-label">YOUR ACCOUNT</div><div class="stat-number stat-text">Active</div><div class="stat-note">Signed in as <?= e($user['email'] ?? '') ?></div></div></div></div>
<section class="panel"><div class="panel-heading"><div><h2>Recently added</h2><p>The latest people added to your directory.</p></div><a class="text-link" href="users.php">View all users <span>→</span></a></div><?php if (!$recentUsers): ?><div class="empty-state"><div class="empty-icon">♧</div><h3>Your directory is ready</h3><p>Add your first user to get started.</p><a class="btn btn-primary" href="create_user.php">Add a user</a></div><?php else: ?><div class="table-responsive"><table class="table align-middle data-table"><thead><tr><th>NAME</th><th>EMAIL ADDRESS</th><th>JOINED</th><th></th></tr></thead><tbody><?php foreach ($recentUsers as $row): ?><tr><td><div class="person-cell"><div class="person-avatar"><?= e(strtoupper(substr($row['name'], 0, 1))) ?></div><span><?= e($row['name']) ?></span></div></td><td class="muted-cell"><?= e($row['email']) ?></td><td class="muted-cell"><?= e(date('M j, Y', strtotime($row['created_at']))) ?></td><td class="text-end"><a class="row-action" href="edit_user.php?id=<?= (int)$row['id'] ?>">Edit</a></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
