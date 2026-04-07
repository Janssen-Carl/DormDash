<?php
// This code checks if a PHP session has not been started yet (PHP_SESSION_NONE)
// If no session exists, it starts a new session using session_start()
// This prevents multiple session_start() calls which could cause errors
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
