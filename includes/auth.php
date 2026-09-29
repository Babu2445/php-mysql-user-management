<?php
declare(strict_types=1);

function requireLogin(): void
{
    if (empty($_SESSION['user_id'])) {
        flash('warning', 'Please sign in to continue.');
        redirect('login.php');
    }
}

function currentUser(PDO $pdo): ?array
{
    if (empty($_SESSION['user_id'])) {
        return null;
    }
    $stmt = $pdo->prepare('SELECT id, name, email, created_at FROM users WHERE id = :id');
    $stmt->execute(['id' => $_SESSION['user_id']]);
    return $stmt->fetch() ?: null;
}
