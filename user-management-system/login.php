<?php
require_once __DIR__ . '/includes/bootstrap.php';
if (!empty($_SESSION['user_id'])) redirect('dashboard.php');
$errors = [];
$email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $email = strtolower(trim((string)($_POST['email'] ?? '')));
    $password = (string)($_POST['password'] ?? '');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 190 || $password === '') $errors[] = 'Enter a valid email address and password.';
    else {
        $stmt = $pdo->prepare('SELECT id, name, email, password FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int)$user['id'];
            unset($_SESSION['csrf_token']);
            flash('success', 'Welcome back, ' . $user['name'] . '.');
            redirect('dashboard.php');
        }
        $errors[] = 'Email or password is incorrect.';
    }
}
$pageTitle = 'Sign in';
require __DIR__ . '/includes/header.php';
?>
<div class="auth-card"><a class="brand auth-brand" href="index.php"><span class="brand-mark">P</span><span>People<span class="brand-light">Desk</span></span></a><div class="auth-heading"><div class="eyebrow">WELCOME BACK</div><h1>Sign in to your account</h1><p>Manage your workspace directory in one place.</p></div>
<?php if ($errors): ?><div class="alert alert-danger"><?php foreach ($errors as $error): ?><div><?= e($error) ?></div><?php endforeach; ?></div><?php endif; ?>
<form method="post" action="login.php" novalidate><?= csrfField() ?><div class="mb-3"><label class="form-label" for="email">Email address</label><input class="form-control" id="email" type="email" name="email" value="<?= e($email) ?>" maxlength="190" autocomplete="email" required></div><div class="mb-4"><label class="form-label" for="password">Password</label><input class="form-control" id="password" type="password" name="password" autocomplete="current-password" required></div><button class="btn btn-primary w-100" type="submit">Sign in <span aria-hidden="true">→</span></button></form><p class="auth-switch">New to PeopleDesk? <a href="register.php">Create an account</a></p></div><div class="auth-footnote">A clearer view of your people.</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
