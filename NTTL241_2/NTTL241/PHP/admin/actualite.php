<?php
require_once __DIR__ . '/../config.php';
nttl_exiger_admin();

$enregistre = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    nttl_exiger_csrf();
    $lignes = preg_split('/\r\n|\r|\n/', trim($_POST['messages'] ?? ''));
    $lignes = array_values(array_filter(array_map('trim', $lignes), fn($l) => $l !== ''));
    nttl_remplacer_ticker_messages($lignes);
    nttl_set_parametre('ticker_actif', isset($_POST['actif']) ? '1' : '0');
    $enregistre = true;
}

$ticker = nttl_get_ticker_messages();
$titre_admin = "Bandeau d'actualité — Administration NTTL241";
$page_admin = 'actualite';
include __DIR__ . '/includes/admin-header.php';
?>
<div class="admin-topbar">
  <div>
    <p class="eyebrow">Contenu du site</p>
    <h1 style="font-size:1.9rem;">Bandeau d'actualité</h1>
  </div>
</div>

<?php if ($enregistre): ?><div class="alert-nttl" style="margin-bottom:1.4rem;">Bandeau mis à jour avec succès.</div><?php endif; ?>

<div class="admin-card" style="max-width:720px;">
  <form method="post" class="form-nttl">
    <?php nttl_champ_csrf(); ?>
    <div class="mb-3">
      <label>Messages (un message par ligne)</label>
      <textarea name="messages" class="form-control" style="min-height:200px;"><?= htmlspecialchars(implode("\n", array_column($ticker, 'message'))) ?></textarea>
    </div>
    <div class="mb-3 form-check">
      <input type="checkbox" class="form-check-input" id="actif" name="actif" <?= nttl_get_parametre('ticker_actif','1') === '1' ? 'checked' : '' ?>>
      <label class="form-check-label" for="actif" style="text-transform:none; font-family:var(--f-body); color:var(--nttl-blanc-casse);">Afficher le bandeau sur le site</label>
    </div>
    <button type="submit" class="btn-nttl plein">Enregistrer</button>
  </form>
</div>
<?php include __DIR__ . '/includes/admin-footer.php'; ?>
