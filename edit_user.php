<?php
require_once __DIR__ . '/includes/bootstrap.php';
requireLogin();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) { flash('danger', 'Choose a valid user.'); redirect('users.php'); }
$stmt = $pdo->prepare('SELECT id, name, email, created_at FROM users WHERE id = :id'); $stmt->execute(['id' => $id]); $record = $stmt->fetch();
if (!$record) { flash('danger', 'That user could not be found.'); redirect('users.php'); }
$errors = []; $name = $record['name']; $email = $record['email'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf(); $name = trim((string)($_POST['name'] ?? '')); $email = strtolower(trim((string)($_POST['email'] ?? ''))); $errors = validateUserInput($name, $email);
    if (!$errors) {
        try { $update = $pdo->prepare('UPDATE users SET name = :name, email = :email WHERE id = :id'); $update->execute(['name' => $name, 'email' => $email, 'id' => $id]); flash('success', 'User details updated.'); redirect('users.php'); }
        catch (PDOException $exception) { if ($exception->getCode() === '23000') $errors[] = 'Another user already has that email address.'; else throw $exception; }
    }
}
$pageTitle = 'Edit user'; $activePage = 'users'; require __DIR__ . '/includes/header.php';
?>
<div class="page-heading"><div><div class="eyebrow">PEOPLE</div><h1>Edit user</h1><p>Update this person’s profile details.</p></div><a class="btn btn-light" href="users.php">← Back to users</a></div>
<section class="panel form-panel"><div class="panel-heading"><div><h2>User details</h2><p>Registered <?= e(date('F j, Y', strtotime($record['created_at']))) ?></p></div></div><?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?><form method="post" action="edit_user.php?id=<?= (int)$id ?>" class="record-form" novalidate><?= csrfField() ?><div class="mb-4"><label class="form-label" for="name">Full name <span class="required-mark">*</span></label><input class="form-control" id="name" name="name" value="<?= e($name) ?>" maxlength="100" required></div><div class="mb-4"><label class="form-label" for="email">Email address <span class="required-mark">*</span></label><input class="form-control" type="email" id="email" name="email" value="<?= e($email) ?>" maxlength="190" required></div><div class="form-actions"><a class="btn btn-light" href="users.php">Cancel</a><button class="btn btn-primary" type="submit">Save changes</button></div></form></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
