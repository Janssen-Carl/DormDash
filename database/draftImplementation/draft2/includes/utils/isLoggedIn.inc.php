<?php

// If already logged in, redirect to the right place
if (isset($_SESSION['user_id'])) {
    $role = $_SESSION['role'] ?? 'customer';
    header('Location: ' . ($role === 'vendor' ? 'vendor.php' : 'index.php'));
    exit;
}
