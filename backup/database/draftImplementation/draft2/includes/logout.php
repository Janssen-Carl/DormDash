<?php

require_once __DIR__ . '/utils/session.inc.php';

// Clear all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to login page
header('Location: ../index.php');
exit();
