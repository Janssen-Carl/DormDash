<?php

function loginUser($conn, $username, $pwd)
{
	require_once __DIR__ . '/session.inc.php';

	if (!($conn instanceof PDO)) {
		require_once __DIR__ . '/../db.php';
		$conn = getDB();
	}


	// check if user exists
	try {
		// use email or username as identifier
		$stmt = $conn->prepare(
			'SELECT id, name, email, password_hash, role
			 FROM users
			 WHERE email = :email OR name = :name
			 LIMIT 1'
		);
		$stmt->execute(['email' => $username, 'name' => $username]);
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
	} catch (Throwable $e) {
		$_SESSION['error'] = 'stmtfailed';


		// // DEBUG: Log the actual error message
		// error_log('Login query failed: ' . $e->getMessage());
		// error_log('Exception class: ' . get_class($e));
		// error_log('Username/identifier tried: ' . $username);
		// // Also set it in session so you can see it on the page (remove in production)
		// $_SESSION['debug_error'] = $e->getMessage();


		header('Location: ../index.php');
		exit();
	}

	if (!$user) {
		$_SESSION['error'] = 'userdoesnotexist';
		header('Location: ../index.php');
		exit();
	}


	// check password

	// plaintext version
	if($pwd !== $user['password_hash']) {
		$_SESSION['error'] = 'incorrectpassword';
		header('Location: ../index.php');
		exit();
	}

	// use this if hashed 
	// if (!password_verify($pwd, $user['password_hash'])) {
	// 	$_SESSION['error'] = 'incorrectpassword';
	// 	header('Location: ../index.php');
	// 	exit();
	// }


	// successful login, set session variables
	session_regenerate_id(true);
	$_SESSION['user_id'] = $user['id'];
	$_SESSION['name'] = $user['name'];
	$_SESSION['email'] = $user['email'];
	$_SESSION['role'] = $user['role'];



	// redirect based on role
	if ($user['role'] === 'vendor') {
		header('Location: ../vendor.php');
		exit();
	}
	if ($user['role'] === 'customer') {
		header('Location: ../customer.php');
		exit();
	}
}