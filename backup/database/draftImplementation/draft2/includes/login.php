<?php

require_once __DIR__ . '/utils/session.inc.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/utils/functions.inc.php';


if (isset($_POST['submit'])) {

      $conn = getDB();

      $username = trim($_POST['uid'] ?? $_POST['username'] ?? '');
      $password = trim($_POST['pwd'] ?? $_POST['password'] ?? '');

      if (empty($username) || empty($password)) {
            $_SESSION['error'] = 'emptyinput';
            header("Location: ../index.php");
            exit();
      }

      loginUser($conn, $username, $password);
}

header("Location: ../index.php");
exit();

