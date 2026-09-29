<?php
require_once __DIR__ . '/includes/bootstrap.php';
requireLogin();
$search = trim((string)($_GET['q'] ?? ''));
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
if ($search !== '') {
    $like = '%' . $search . '%';
    $countStmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE name LIKE :name OR email LIKE :email');
    $countStmt->execute(['name' => $like, 'email' => $like]);
} else $countStmt = $pdo->query('SELECT COUNT(*) FROM users');
$total = (int)$countStmt->fetchColumn();
$pages = max(1, (int)ceil($total / $perPage));
$page = min($page, $pages);
$offset = ($page - 1) * $perPage;
if ($search !== '') {
    $stmt = $pdo->prepare('SELECT id, name, email, created_at FROM users WHERE name LIKE :name OR email LIKE :email ORDER BY created_at DESC, id DESC LIMIT :limit OFFSET :offset');
    $stmt->bindValue(':name', $like); $stmt->bindValue(':email', $like);
} else $stmt = $pdo->prepare('SELECT id, name, email, created_at FROM users ORDER BY created_at DESC, id DESC LIMIT :limit OFFSET :offset');
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT); $stmt->bindValue(':offset', $offset, PDO::PARAM_INT); $stmt->execute();
$rows = $stmt->fetchAll();
$pageTitle = 'User directory'; $activePage = 'users';
require __DIR__ . '/includes/header.php';
?>
<div class="page-heading"><div><div class="eyebrow">PEOPLE</div><h1>User directory</h1><p>Manage the people who have access to your workspace.</p></div><a class="btn btn-primary" href="create_user.php"><span>＋</span> Add user</a></div>
<section class="panel"><div class="panel-heading directory-heading"><div><h2>All users <span class="count-pill"><?= number_format($total) ?></span></h2><p>Browse, search, and update user details.</p></div><form class="search-form" method="get" action="users.php"><label class="visually-hidden" for="q">Search users</label><input class="form-control" type="search" id="q" name="q" value="<?= e($search) ?>" placeholder="Search name or email"><button class="btn btn-outline-secondary" type="submit">Search</button><?php if ($search !== ''): ?><a class="clear-search" href="users.php">Clear</a><?php endif; ?></form></div>
<?php if (!$rows): ?><div class="empty-state"><div class="empty-icon">⌕</div><h3><?= $search !== '' ? 'No matching users' : 'No users yet' ?></h3><p><?= $search !== '' ? 'Try a different name or email.' : 'Add a user to begin building your directory.' ?></p><?php if ($search === ''): ?><a class="btn btn-primary" href="create_user.php">Add a user</a><?php endif; ?></div><?php else: ?><div class="table-responsive"><table class="table align-middle data-table"><thead><tr><th>NAME</th><th>EMAIL ADDRESS</th><th>REGISTRATION DATE</th><th class="text-end">ACTIONS</th></tr></thead><tbody><?php foreach ($rows as $row): ?><tr><td><div class="person-cell"><div class="person-avatar"><?= e(strtoupper(substr($row['name'], 0, 1))) ?></div><span><?= e($row['name']) ?></span></div></td><td class="muted-cell"><?= e($row['email']) ?></td><td class="muted-cell"><?= e(date('M j, Y', strtotime($row['created_at']))) ?></td><td><div class="table-actions"><a class="row-action" href="edit_user.php?id=<?= (int)$row['id'] ?>">Edit</a><form method="post" action="delete_user.php" class="delete-form" data-confirm="Delete <?= e($row['name']) ?>? This action cannot be undone."><?= csrfField() ?><input type="hidden" name="id" value="<?= (int)$row['id'] ?>"><button class="row-action danger-link" type="submit">Delete</button></form></div></td></tr><?php endforeach; ?></tbody></table></div><div class="pagination-bar"><span>Showing <?= $total ? $offset + 1 : 0 ?>–<?= min($offset + $perPage, $total) ?> of <?= number_format($total) ?></span><?php if ($pages > 1): ?><nav aria-label="User pages"><ul class="pagination pagination-sm mb-0"><?php for ($i = 1; $i <= $pages; $i++): ?><li class="page-item <?= $i === $page ? 'active' : '' ?>"><a class="page-link" href="?<?= http_build_query(['q' => $search, 'page' => $i]) ?>"><?= $i ?></a></li><?php endfor; ?></ul></nav><?php endif; ?></div><?php endif; ?></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
