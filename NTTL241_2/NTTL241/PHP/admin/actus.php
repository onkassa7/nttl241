<?php
require_once __DIR__ . '/../config.php';
nttl_exiger_admin();

$erreur = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    nttl_exiger_csrf();
    if (($_POST['action'] ?? '') === 'ajouter') {
        $titre = trim($_POST['titre'] ?? '');
        $extrait = trim($_POST['extrait'] ?? '');
        $contenu = trim($_POST['contenu'] ?? '');
        if ($titre !== '' && $contenu !== '') {
            $image = nttl_upload_image('image', NTTL_UPLOAD_DIR_ACTUS, 'actu');
            $publie = isset($_POST['publie']) ? 1 : 0;
            nttl_ajouter_actu($titre, $extrait, $contenu, $image ?: null, $publie);
        } else {
            $erreur = "Le titre et le contenu sont obligatoires.";
        }
    } elseif (($_POST['action'] ?? '') === 'supprimer') {
        nttl_supprimer_actu((int)($_POST['id'] ?? 0));
    }
    if (!$erreur) { header('Location: actus.php'); exit; }
}

$actus = nttl_get_toutes_actus();
$titre_admin = "Actus / Blog — Administration NTTL241";
$page_admin = 'actus';
include __DIR__ . '/includes/admin-header.php';
?>
<div class="admin-topbar">
  <div>
    <p class="eyebrow">Contenu du site</p>
    <h1 style="font-size:1.9rem;">Actus / Blog</h1>
  </div>
</div>

<?php if ($erreur): ?><div class="alert-nttl" style="border-color:#ff5b5b; color:#ff9d9d; margin-bottom:1.2rem;"><?= htmlspecialchars($erreur) ?></div><?php endif; ?>

<div class="admin-card" style="margin-bottom:26px;">
  <h3 style="font-size:1.1rem; text-transform:none; font-family:var(--f-body); font-weight:700;">Publier une actu</h3>
  <form method="post" enctype="multipart/form-data" class="form-nttl row g-3" style="margin-top:.6rem;">
    <?php nttl_champ_csrf(); ?>
    <input type="hidden" name="action" value="ajouter">
    <div class="col-md-6"><label>Titre</label><input type="text" name="titre" class="form-control" required></div>
    <div class="col-md-6"><label>Image (jpg/png/webp)</label><input type="file" name="image" accept="image/png,image/jpeg,image/webp" class="form-control"></div>
    <div class="col-12"><label>Extrait (résumé court affiché dans les listes)</label><input type="text" name="extrait" class="form-control" maxlength="300"></div>
    <div class="col-12"><label>Contenu</label><textarea name="contenu" class="form-control" style="min-height:180px;" required></textarea></div>
    <div class="col-12 form-check">
      <input type="checkbox" class="form-check-input" id="publie" name="publie" checked>
      <label class="form-check-label" for="publie" style="text-transform:none; font-family:var(--f-body); color:var(--nttl-blanc-casse);">Publier immédiatement</label>
    </div>
    <div class="col-12"><button class="btn-nttl plein" type="submit">Publier</button></div>
  </form>
</div>

<div class="admin-card">
  <table class="table" style="color:var(--nttl-blanc-casse); margin:0;">
    <thead><tr><th>Image</th><th>Titre</th><th>Date</th><th>Statut</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($actus as $a): ?>
      <tr>
        <td>
          <?php if (!empty($a['image'])): ?>
            <img src="<?= htmlspecialchars(NTTL_UPLOAD_WEB_ACTUS . $a['image']) ?>" style="width:56px;height:36px;object-fit:cover;border-radius:4px;">
          <?php else: ?><span class="text-muted">—</span><?php endif; ?>
        </td>
        <td><?= htmlspecialchars($a['titre']) ?></td>
        <td class="text-muted" style="font-family:var(--f-mono); font-size:.8rem;"><?= htmlspecialchars(date('d.m.Y', strtotime($a['date_publication']))) ?></td>
        <td><?= $a['publie'] ? '<span class="text-jaune">Publiée</span>' : '<span class="text-muted">Brouillon</span>' ?></td>
        <td>
          <?php if ($a['publie']): ?><a href="<?= nttl_url('/O/actu.php') ?>?slug=<?= urlencode($a['slug']) ?>" target="_blank" class="btn-nttl" style="margin-right:6px;">Voir</a><?php endif; ?>
          <form method="post" style="display:inline;" onsubmit="return confirm('Supprimer cette actu ?');">
    <?php nttl_champ_csrf(); ?>
            <input type="hidden" name="action" value="supprimer">
            <input type="hidden" name="id" value="<?= $a['id'] ?>">
            <button class="btn-nttl" type="submit">Supprimer</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/includes/admin-footer.php'; ?>
