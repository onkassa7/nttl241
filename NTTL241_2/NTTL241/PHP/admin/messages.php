<?php
require_once __DIR__ . '/../config.php';
nttl_exiger_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'supprimer') {
    nttl_exiger_csrf();
    nttl_supprimer_message_contact((int)($_POST['id'] ?? 0));
    header('Location: messages.php');
    exit;
}

$messages = nttl_get_messages_contact();
$titre_admin = "Messages reçus — Administration NTTL241";
$page_admin = 'messages';
include __DIR__ . '/includes/admin-header.php';
?>
<div class="admin-topbar">
  <div>
    <p class="eyebrow">Formulaire de contact</p>
    <h1 style="font-size:1.9rem;">Messages reçus</h1>
  </div>
</div>

<?php if (empty($messages)): ?>
  <div class="admin-card"><p class="text-muted" style="margin:0;">Aucun message reçu pour le moment.</p></div>
<?php else: ?>
  <?php foreach ($messages as $m): ?>
  <div class="admin-card" style="margin-bottom:18px;">
    <div class="admin-topbar" style="margin-bottom:10px;">
      <div>
        <strong><?= htmlspecialchars($m['nom']) ?></strong>
        <span class="text-muted"> — <?= htmlspecialchars($m['email']) ?></span>
        <div class="text-muted" style="font-family:var(--f-mono); font-size:.75rem; margin-top:4px;"><?= htmlspecialchars($m['date_envoi']) ?></div>
      </div>
      <form method="post" onsubmit="return confirm('Supprimer ce message ?');">
    <?php nttl_champ_csrf(); ?>
        <input type="hidden" name="action" value="supprimer">
        <input type="hidden" name="id" value="<?= $m['id'] ?>">
        <button class="btn-nttl" type="submit">Supprimer</button>
      </form>
    </div>
    <?php if (!empty($m['sujet'])): ?><p><strong>Sujet :</strong> <?= htmlspecialchars($m['sujet']) ?></p><?php endif; ?>
    <p class="text-muted" style="white-space:pre-wrap;"><?= htmlspecialchars($m['message']) ?></p>
  </div>
  <?php endforeach; ?>
<?php endif; ?>
<?php include __DIR__ . '/includes/admin-footer.php'; ?>
