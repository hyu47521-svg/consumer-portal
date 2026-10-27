<?php
// Shared page header and footer used by all pages

function render_header(string $title): void
{
    $user = current_user();
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($title) ?> | <?= e(SITE_NAME) ?></title>
<link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<header class="site-header">
  <div class="container nav-wrap">
    <a href="/" class="logo"><?= e(SITE_NAME) ?></a>
    <button class="menu-toggle" aria-label="Open menu" aria-expanded="false">&#9776;</button>
    <nav class="main-nav">
      <a href="/rights.php">Know Your Rights</a>
      <a href="/help.php">Get Help</a>
      <a href="/track.php">Track Complaint</a>
      <?php if ($user): ?>
        <a href="/dashboard.php">My Dashboard</a>
        <a href="/logout.php" class="nav-btn-outline">Log out</a>
      <?php else: ?>
        <a href="/login.php" class="nav-btn-outline">Log in</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container section">
<?php
}

function render_footer(): void
{
    ?>
</main>
<footer class="site-footer">
  <div class="container disclaimer">
    This is an independent consumer information service. It is not a government agency.
    &copy; <?= date('Y') ?> <?= e(SITE_NAME) ?>
  </div>
</footer>
<script>
const btn = document.querySelector('.menu-toggle');
const nav = document.querySelector('.main-nav');
btn.addEventListener('click', () => {
  const open = nav.classList.toggle('open');
  btn.setAttribute('aria-expanded', open);
});
</script>
</body>
</html>
<?php
}

function status_badge(string $status): string
{
    $class = match ($status) {
        'Resolved' => 'badge badge-resolved',
        'Closed'   => 'badge badge-closed',
        default    => 'badge',
    };
    return '<span class="' . $class . '">' . e($status) . '</span>';
}
