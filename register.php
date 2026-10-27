<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/layout.php';

if (current_user()) { header('Location: /dashboard.php'); exit; }

$errors = [];
$old = ['full_name' => '', 'email' => '', 'phone' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $old['full_name'] = trim($_POST['full_name'] ?? '');
    $old['email']     = strtolower(trim($_POST['email'] ?? ''));
    $old['phone']     = trim($_POST['phone'] ?? '');
    $password         = $_POST['password'] ?? '';
    $confirm          = $_POST['confirm'] ?? '';

    if ($old['full_name'] === '' || mb_strlen($old['full_name']) > 120) {
        $errors[] = 'Please enter your full name.';
    }
    if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    if (!$errors) {
        $check = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $check->execute([$old['email']]);
        if ($check->fetch()) {
            $errors[] = 'An account with this email already exists. Please log in.';
        }
    }

    if (!$errors) {
        $stmt = $pdo->prepare(
            'INSERT INTO users (full_name, email, phone, password_hash, role) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $old['full_name'],
            $old['email'],
            $old['phone'] !== '' ? $old['phone'] : null,
            password_hash($password, PASSWORD_DEFAULT),
            'consumer',
        ]);

        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id'        => (int)$pdo->lastInsertId(),
            'full_name' => $old['full_name'],
            'email'     => $old['email'],
            'role'      => 'consumer',
        ];
        header('Location: /dashboard.php');
        exit;
    }
}

render_header('Create account');
?>
<div class="form-card">
  <h1 style="font-size:1.7rem">Create your account</h1>
  <p class="muted">Track your complaints and receive updates in one place.</p>

  <?php foreach ($errors as $err): ?>
    <div class="alert alert-error"><?= e($err) ?></div>
  <?php endforeach; ?>

  <form method="post" action="/register.php">
    <?= csrf_field() ?>
    <label>Full name
      <input name="full_name" value="<?= e($old['full_name']) ?>" required maxlength="120">
    </label>
    <label>Email
      <input type="email" name="email" value="<?= e($old['email']) ?>" required maxlength="190">
    </label>
    <label>Phone (optional)
      <input type="tel" name="phone" value="<?= e($old['phone']) ?>" maxlength="30">
    </label>
    <label>Password (at least 8 characters)
      <input type="password" name="password" required minlength="8" autocomplete="new-password">
    </label>
    <label>Confirm password
      <input type="password" name="confirm" required minlength="8" autocomplete="new-password">
    </label>
    <button type="submit" class="btn btn-primary btn-block" style="margin-top:20px">Create account</button>
  </form>
  <p class="center" style="margin-top:18px">Already registered? <a href="/login.php">Log in</a></p>
</div>
<?php
render_footer();
