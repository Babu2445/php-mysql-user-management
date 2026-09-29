<?php
require_once __DIR__ . '/includes/bootstrap.php';
redirect(!empty($_SESSION['user_id']) ? 'dashboard.php' : 'login.php');
