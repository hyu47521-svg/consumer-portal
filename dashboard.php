<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/layout.php';

require_login();
$user = current_user();

$stmt = $pdo->prepare(
    'SELECT reference_no, business_name, category, status, updated_at
     FROM complaints WHERE user_id = ? ORDER BY created_at DESC'
);
$stmt->execute([$user['id']]);
$complaints = $stmt->fetchAll();

render_header('My Dashboard');
?>
<div class="page-head">
  <div>
    <h1 style="font-size:1.8rem">Welcome, <?= e($user['full_name']) ?></h1>
    <p class="muted">Here are your complaints and their current status.</p>
  </div>
  <a href="/complaint.php" class="btn btn-primary">+ File a complaint</a>
</div>

<div class="card">
  <?php if (!$complaints): ?>
    <p class="muted">You haven't filed any complaints yet.</p>
  <?php else: ?>
    <div class="table-wrap">
      <table>
        <thead>
          <tr><th>Reference</th><th>Business</th><th>Type</th><th>Status</th><th>Last updated</th></tr>
        </thead>
        <tbody>
        <?php foreach ($complaints as $c): ?>
          <tr>
            <td><strong><?= e($c['reference_no']) ?></strong></td>
            <td><?= e($c['business_name']) ?></td>
            <td><?= e(ucwords(str_replace('_', ' ', $c['category']))) ?></td>
            <td><?= status_badge($c['status']) ?></td>
            <td><?= e(date('M j, Y', strtotime($c['updated_at']))) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
<?php
render_footer();
