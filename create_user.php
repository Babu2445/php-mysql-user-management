<?php
require_once __DIR__ . '/includes/bootstrap.php';
requireLogin();
$errors = []; $name = ''; $email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf(); $name = trim((string)($_POST['name'] ?? '')); $email = strtolower(trim((string)($_POST['email'] ?? '')));
    $errors = validateUserInput($name, $email);
    if (!$errors) {
        try { $stmt = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)'); $stmt->execute(['name' => $name, 'email' => $email, 'password' => password_hash(bin2hex(random_bytes(32)), PASSWORD_DEFAULT)]); flash('success', 'User added successfully.'); redirect('users.php'); }
        catch (PDOException $exception) { if ($exception->getCode() === '23000') $errors[] = 'A user with that email already exists.'; else throw $exception; }
    }
}
$pageTitle = 'Add user'; $activePage = 'users'; require __DIR__ . '/includes/header.php';
?>
<div class="page-heading"><div><div class="eyebrow">PEOPLE</div><h1>Add a user</h1><p>Create a profile in your workspace directory.</p></div><a class="btn btn-light" href="users.php">← Back to users</a></div>
<section class="panel form-panel"><div class="panel-heading"><div><h2>User details</h2><p>Fields marked with an asterisk are required.</p></div></div><?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?><form method="post" action="create_user.php" class="record-form" novalidate><?= csrfField() ?><div class="mb-4"><label class="form-label" for="name">Full name <span class="required-mark">*</span></label><input class="form-control" id="name" name="name" value="<?= e($name) ?>" maxlength="100" autocomplete="name" required><div class="form-text">Enter the person’s first and last name.</div></div><div class="mb-4"><label class="form-label" for="email">Email address <span class="required-mark">*</span></label><input class="form-control" type="email" id="email" name="email" value="<?= e($email) ?>" maxlength="190" autocomplete="email" required><div class="form-text">This email must not already be in the directory.</div></div><div class="form-note">A random, unusable credential is stored for administrator-created accounts. They can sign in by creating an account with their email only after the existing record is removed; this sample does not include invitations or password resets.</div><div class="form-actions"><a class="btn btn-light" href="users.php">Cancel</a><button class="btn btn-primary" type="submit">Create user</button></div></form></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
