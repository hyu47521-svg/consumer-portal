<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/layout.php';

if (current_user()) { header('Location: /dashboard.php'); exit; }

$error = '';
$email = '';
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $email    = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    // Lock out after 5 failed attempts within 15 minutes
    $recent = $pdo->prepare(
        'SELECT COUNT(*) FROM login_attempts WHERE email = ? AND created_at > (NOW() - INTERVAL 15 MINUTE)'
    );
    $recent->execute([$email]);

    if ((int)$recent->fetchColumn() >= 5) {
        $error = 'Too many failed attempts. Please wait 15 minutes and try again.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && $user['is_active'] && password_verify($password, $user['password_hash'])) {
            $pdo->prepare('DELETE FROM login_attempts WHERE email = ?')->execute([$email]);
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id'        => (int)$user['id'],
                'full_name' => $user['full_name'],
                'email'     => $user['email'],
                'role'      => $user['role'],
            ];
            header('Location: /dashboard.php');
            exit;
        }

        $pdo->prepare('INSERT INTO login_attempts (email, ip_address) VALUES (?, ?)')
            ->execute([$email, $ip]);
        // Same message whether the email exists or not, so attackers can't find accounts
        $error = 'Incorrect email or password.';
    }
}

render_header('Log in');
?>
<div class="form-card">
  <h1 style="font-size:1.7rem">Log in</h1>
  <p class="muted">Access your complaints and updates.</p>

  <?php if ($error): ?>
    <div class="alert alert-error"><?= e($error) ?></div>
  <?php endif; ?>

  <form method="post" action="/login.php">
    <?= csrf_field() ?>
    <label>Email
      <input type="email" name="email" value="<?= e($email) ?>" required>
    </label>
    <label>Password
      <input type="password" name="password" required autocomplete="current-password">
    </label>
    <button type="submit" class="btn btn-primary btn-block" style="margin-top:20px">Log in</button>
  </form>
  <p class="center" style="margin-top:18px">New here? <a href="/register.php">Create an account</a></p>
</div>
<?php
render_footer();
