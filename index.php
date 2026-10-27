<?php
require_once __DIR__ . '/config.php';
$user = current_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e(SITE_NAME) ?> | Know Your Rights, Report Fraud</title>
<meta name="description" content="Understand your consumer rights, avoid scams, report fraud, and track your complaint.">
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

<main>
  <section class="hero">
    <div class="container">
      <h1>Protect yourself. Report problems. Get answers.</h1>
      <p class="hero-sub">Learn your consumer rights, spot scams early, report unfair treatment, and follow your complaint from start to finish.</p>
      <div class="hero-actions">
        <a href="/complaint.php" class="btn btn-primary btn-lg">File a Consumer Complaint</a>
        <a href="/track.php" class="btn btn-secondary btn-lg">Track My Complaint</a>
      </div>
      <p class="hero-note">Free to use. Your information is kept private and secure.</p>
    </div>
  </section>

  <section class="container section">
    <h2>What can you report?</h2>
    <div class="cards">
      <a class="card" href="/complaint.php?category=scam_fraud">
        <h3>Scams and fraud</h3>
        <p>Fake offers, phishing, identity theft, or payment scams.</p>
      </a>
      <a class="card" href="/complaint.php?category=billing_dispute">
        <h3>Billing disputes</h3>
        <p>Wrong charges, hidden fees, or refunds you were never given.</p>
      </a>
      <a class="card" href="/complaint.php?category=unfair_practice">
        <h3>Unfair business practices</h3>
        <p>Misleading advertising, pressure tactics, or contract problems.</p>
      </a>
      <a class="card" href="/complaint.php?category=product_service">
        <h3>Product or service issues</h3>
        <p>Faulty goods, poor service, or warranties not honored.</p>
      </a>
    </div>
  </section>

  <section class="section section-alt">
    <div class="container">
      <h2>Know Your Rights</h2>
      <p class="section-intro">Reliable, plain-language information to help you make informed decisions.</p>
      <div class="cards">
        <a class="card" href="/rights.php#refunds"><h3>Refunds and returns</h3><p>What businesses must offer and when.</p></a>
        <a class="card" href="/rights.php#contracts"><h3>Contracts and cancellations</h3><p>Your options before and after you sign.</p></a>
        <a class="card" href="/rights.php#privacy"><h3>Privacy and data</h3><p>How your personal information should be handled.</p></a>
      </div>
      <p class="center"><a href="/rights.php" class="text-link">Read all rights guides &rarr;</a></p>
    </div>
  </section>

  <section class="container section">
    <h2>How we can help</h2>
    <ol class="steps">
      <li><strong>Describe the problem.</strong> Tell us what happened, when, and which business was involved.</li>
      <li><strong>Add evidence.</strong> Upload receipts, screenshots, contracts, or messages.</li>
      <li><strong>Track progress.</strong> Use your reference number to see every status update.</li>
    </ol>
    <p class="center"><a href="/help.php" class="text-link">Get step-by-step help &rarr;</a></p>
  </section>

  <section class="alert-banner">
    <div class="container">
      <strong>Scam alert:</strong> Never share your password or one-time codes with anyone who contacts you unexpectedly. Legitimate organizations will not ask for them.
    </div>
  </section>
</main>

<footer class="site-footer">
  <div class="container footer-grid">
    <div>
      <strong><?= e(SITE_NAME) ?></strong>
      <p class="muted">Helping consumers understand their rights and resolve problems.</p>
    </div>
    <div>
      <strong>Quick links</strong>
      <a href="/complaint.php">File a complaint</a>
      <a href="/track.php">Track a complaint</a>
      <a href="/rights.php">Know your rights</a>
      <a href="/help.php">Get help</a>
    </div>
    <div>
      <strong>Contact</strong>
      <a href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= e(CONTACT_EMAIL) ?></a>
      <a href="/privacy.php">Privacy policy</a>
      <a href="/terms.php">Terms of use</a>
    </div>
  </div>
  <div class="container disclaimer">
    This is an independent consumer information service. It is not a government agency. For emergencies, contact your local emergency number.
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
