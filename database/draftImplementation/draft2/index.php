<?php
session_start();

// If already logged in, redirect to the right place
if (isset($_SESSION['user_id'])) {
    $role = $_SESSION['role'] ?? 'customer';
    header('Location: ' . ($role === 'vendor' ? 'vendor.php' : 'index.php'));
    exit;
}

// Flash error from handler
$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);

// Dev: fetch users for the debug panel
$users = [];
try {
    require_once __DIR__ . '/includes/db.php';
    $pdo   = getDB();
    $stmt  = $pdo->query("SELECT name, role, password_hash FROM users ORDER BY role, name");
    $users = $stmt->fetchAll();
} catch (Throwable $e) {
    // silently skip if db not ready
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — DormDash</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Anybody:ital,wght@0,400;0,700;0,900;1,900&family=DM+Sans:wght@400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

<div class="wrap">

  <!-- Brand -->
  <div class="brand">
    <div class="brand-logo">
      <div class="brand-name">Dorm<span>Dash</span></div>
    </div>
    <p class="brand-sub">Campus delivery, straight to your door</p>
  </div>

  <!-- Login card -->
  <div class="card">
    <div class="card-title">Welcome back</div>
    <p class="card-sub">Sign in to your DormDash account</p>

    <?php if ($error): ?>
      <div class="error-box">⚠ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form class="form" action="includes/login.php" method="POST">

      <!-- Role selector -->
      <div class="fld">
        <label>I am a</label>
        <div class="role-toggle">
          <input type="radio" name="role" id="role_customer" value="customer" checked>
          <label for="role_customer"><span class="role-dot"></span> Customer</label>

          <input type="radio" name="role" id="role_vendor" value="vendor">
          <label for="role_vendor"><span class="role-dot"></span> Vendor</label>
        </div>
      </div>

      <!-- Username -->
      <div class="fld">
        <label>Username</label>
        <div class="input-wrap">
          <input
            type="text"
            id="username"
            name="username"
            placeholder="Enter your username"
            value="<?= htmlspecialchars($_GET['u'] ?? '') ?>"
            autocomplete="username"
            required
          >
        </div>
      </div>

      <!-- Password -->
      <div class="fld">
        <label>Password</label>
        <div class="input-wrap">
          <input
            type="password"
            id="password"
            name="password"
            placeholder="Enter your password"
            autocomplete="current-password"
            required
          >
        </div>
      </div>

      <button class="btn-submit" type="submit">Sign In →</button>

      <p class="form-foot">
        Don't have an account? <a href="register.php">Create one</a>
      </p>

    </form>
  </div>

  
  <!-- ── Dev panel ── -->
  <div class="dev-panel" id="devPanel">
    <button class="dev-toggle" onclick="toggleDev()">
      <span class="dev-toggle-left">
        <span class="dev-dot <?= empty($users) ? 'off' : '' ?>"></span>
        <span>dev — registered users (<?= count($users) ?>)</span>
      </span>
      <span class="dev-chevron">▼</span>
    </button>

    <div class="dev-body">
      <?php if (empty($users)): ?>
        <p class="dev-empty">No users found — DB may not be connected.</p>
      <?php else:
        $byRole = [];
        foreach ($users as $u) $byRole[$u['role']][] = $u;
        ksort($byRole); // vendors before customers alphabetically
      ?>

        <?php foreach ($byRole as $role => $group): ?>
          <div class="dev-role-group"><?= htmlspecialchars($role) ?>s</div>

          <?php foreach ($group as $u):
            $uname = htmlspecialchars($u['username'] ?? $u['name']);
            $uname_js = addslashes($u['username'] ?? $u['name']);
            $role_js  = addslashes($role);
          ?>
            <div class="dev-user-row">

              <!-- User info -->
              <div class="dev-user-info">
                <span class="dev-user-name"><?= htmlspecialchars($u['name']) ?></span>
                <div class="dev-user-meta">
                  <span class="dev-user-username">@<?= $uname ?></span>
                  <span class="role-chip <?= htmlspecialchars($role) ?>"><?= htmlspecialchars($role) ?></span>
                </div>
              </div>

              <!-- Quick login button -->
              <div class="dev-user-right">
                <button
                  type="button"
                  class="quick-btn <?= $role === 'vendor' ? 'vendor' : '' ?>"
                  onclick="quickLogin('<?= $uname_js ?>', '<?= $role_js ?>')"
                  title="Log in as <?= $uname ?>"
                >
                  ↗ login
                </button>
              </div>

            </div>
          <?php endforeach; ?>
        <?php endforeach; ?>

      <?php endif; ?>
    </div>
  </div>
  
</div>

<script>
function toggleDev() {
  document.getElementById('devPanel').classList.toggle('open');
}

function quickLogin(username, role) {
  const usernameEl = document.getElementById('username');
  const passwordEl = document.getElementById('password');
  const roleEl     = document.querySelector('input[name="role"][value="' + role + '"]');
  const flagEl     = document.getElementById('devQuickFlag');

  // Set role radio button
  if (roleEl) roleEl.checked = true;

  // Fill fields
  usernameEl.value = username;
  passwordEl.value = '________'; // visual placeholder — handler uses dev_quick flag

  // Tell the handler to skip password verification
  flagEl.value = '1';

  // Flash both fields so the fill is visually obvious
  [usernameEl, passwordEl].forEach(el => {
    el.classList.remove('field-flash');
    void el.offsetWidth; // force reflow to restart animation
    el.classList.add('field-flash');
  });

  // Brief pause so the user sees it, then submit
  setTimeout(() => document.getElementById('loginForm').submit(), 350);
}

</script>
</body>
</html>