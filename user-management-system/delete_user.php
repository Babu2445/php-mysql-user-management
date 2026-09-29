<?php
require_once __DIR__ . '/includes/bootstrap.php';
requireLogin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); header('Allow: POST'); exit('Method not allowed.'); }
verifyCsrf();
$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
if (!$id || $id < 1) { flash('danger', 'Choose a valid user.'); redirect('users.php'); }
if ((int)$id === (int)$_SESSION['user_id']) { flash('warning', 'You cannot delete the account you are currently using.'); redirect('users.php'); }
$stmt = $pdo->prepare('DELETE FROM users WHERE id = :id'); $stmt->execute(['id' => $id]);
flash($stmt->rowCount() ? 'success' : 'warning', $stmt->rowCount() ? 'User deleted.' : 'That user was already removed.');
redirect('users.php');
