<?php
$pageTitle = $pageTitle ?? 'User Management';
$activePage = $activePage ?? '';
$loggedIn = !empty($_SESSION['user_id']);
$navUser = $loggedIn ? currentUser($pdo) : null;
$flashMessage = takeFlash();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f7fb">
    <title><?= e($pageTitle) ?> · PeopleDesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php if ($loggedIn): ?>
<div class="app-shell">
    <aside class="sidebar" id="sidebar">
        <a class="brand" href="dashboard.php"><span class="brand-mark">P</span><span>People<span class="brand-light">Desk</span></span></a>
        <div class="sidebar-label">WORKSPACE</div>
        <nav class="nav flex-column gap-1">
            <a class="nav-link <?= $activePage === 'dashboard' ? 'active' : '' ?>" href="dashboard.php"><span>▦</span> Overview</a>
            <a class="nav-link <?= $activePage === 'users' ? 'active' : '' ?>" href="users.php"><span>♧</span> User directory</a>
            <a class="nav-link" href="create_user.php"><span>＋</span> Add a user</a>
        </nav>
        <div class="sidebar-bottom"><div class="sidebar-label">ACCOUNT</div><form method="post" action="logout.php" class="sidebar-logout"><?= csrfField() ?><button class="nav-link" type="submit"><span>↗</span> Sign out</button></form></div>
    </aside>
    <main class="main-area">
        <header class="topbar"><button class="menu-toggle" id="menuToggle" aria-label="Toggle navigation">☰</button><div class="topbar-label">Workspace <span>/</span> <?= e($pageTitle) ?></div><div class="profile"><div class="avatar"><?= e(strtoupper(substr($navUser['name'] ?? 'U', 0, 1))) ?></div><div class="profile-name"><?= e($navUser['name'] ?? 'Account') ?></div></div></header>
        <div class="page-content">
<?php else: ?>
<div class="auth-page">
<?php endif; ?>
<?php if ($flashMessage): ?>
<div class="alert alert-<?= e($flashMessage['type']) ?> alert-dismissible fade show" role="alert"><?= e($flashMessage['message']) ?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
<?php endif; ?>
