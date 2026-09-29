<?php
require_once __DIR__ . '/includes/bootstrap.php';
if (!empty($_SESSION['user_id'])) redirect('dashboard.php');
$errors = [];
$name = $email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $name = trim((string)($_POST['name'] ?? ''));
    $email = strtolower(trim((string)($_POST['email'] ?? '')));
    $password = (string)($_POST['password'] ?? '');
    $confirm = (string)($_POST['confirm_password'] ?? '');
    $errors = validateUserInput($name, $email);
    if (strlen($password) < 8 || strlen($password) > 72) $errors[] = 'Password must be between 8 and 72 characters.';
    if ($password !== $confirm) $errors[] = 'Passwords do not match.';
    if (!$errors) {
        try {
            $stmt = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
            $stmt->execute(['name' => $name, 'email' => $email, 'password' => password_hash($password, PASSWORD_DEFAULT)]);
            flash('success', 'Your account is ready. Please sign in.');
            redirect('login.php');
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000') $errors[] = 'An account with that email already exists.';
            else throw $exception;
        }
    }
}
$pageTitle = 'Create account';
require __DIR__ . '/includes/header.php';
?>
<div class="auth-card"><a class="brand auth-brand" href="index.php"><span class="brand-mark">P</span><span>People<span class="brand-light">Desk</span></span></a><div class="auth-heading"><div class="eyebrow">GET STARTED</div><h1>Create your account</h1><p>Set up your PeopleDesk workspace profile.</p></div>
<?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form method="post" action="register.php" novalidate><?= csrfField() ?><div class="mb-3"><label class="form-label" for="name">Full name</label><input class="form-control" id="name" name="name" value="<?= e($name) ?>" maxlength="100" autocomplete="name" required></div><div class="mb-3"><label class="form-label" for="email">Email address</label><input class="form-control" id="email" type="email" name="email" value="<?= e($email) ?>" maxlength="190" autocomplete="email" required></div><div class="mb-3"><label class="form-label" for="password">Password</label><input class="form-control" id="password" type="password" name="password" minlength="8" autocomplete="new-password" required><div class="form-text">Use at least 8 characters.</div></div><div class="mb-4"><label class="form-label" for="confirm_password">Confirm password</label><input class="form-control" id="confirm_password" type="password" name="confirm_password" autocomplete="new-password" required></div><button class="btn btn-primary w-100" type="submit">Create account <span aria-hidden="true">→</span></button></form><p class="auth-switch">Already have an account? <a href="login.php">Sign in</a></p></div><div class="auth-footnote">A clearer view of your people.</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
