<?php
require_once __DIR__ . '/../config.php';
nttl_exiger_admin();

$erreur = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    nttl_exiger_csrf();
    if (($_POST['action'] ?? '') === 'ajouter') {
        $nom = trim($_POST['nom'] ?? '');
        $genre = trim($_POST['genre'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        if ($nom !== '') {
            $photo = nttl_upload_image('photo', NTTL_UPLOAD_DIR_ARTISTES, 'artiste');
            nttl_ajouter_artiste($nom, $genre ?: 'Non renseigné', $bio, $photo ?: null);
        } else {
            $erreur = "Le nom est obligatoire.";
        }
    } elseif (($_POST['action'] ?? '') === 'supprimer') {
        nttl_supprimer_artiste((int)($_POST['id'] ?? 0));
    }
    if (!$erreur) { header('Location: artistes.php'); exit; }
}

$artistes = nttl_get_artistes();
$titre_admin = "Nos artistes — Administration NTTL241";
$page_admin = 'artistes';
include __DIR__ . '/includes/admin-header.php';
?>
<div class="admin-topbar">
  <div>
    <p class="eyebrow">Contenu du site</p>
    <h1 style="font-size:1.9rem;">Nos artistes</h1>
  </div>
</div>

<?php if ($erreur): ?><div class="alert-nttl" style="border-color:#ff5b5b; color:#ff9d9d; margin-bottom:1.2rem;"><?= htmlspecialchars($erreur) ?></div><?php endif; ?>

<div class="admin-card" style="margin-bottom:26px;">
  <h3 style="font-size:1.1rem; text-transform:none; font-family:var(--f-body); font-weight:700;">Ajouter un artiste</h3>
  <form method="post" enctype="multipart/form-data" class="form-nttl row g-3" style="margin-top:.6rem;">
    <?php nttl_champ_csrf(); ?>
    <input type="hidden" name="action" value="ajouter">
    <div class="col-md-3"><label>Nom</label><input type="text" name="nom" class="form-control" required></div>
    <div class="col-md-3"><label>Genre musical</label><input type="text" name="genre" class="form-control"></div>
    <div class="col-md-3"><label>Courte bio</label><input type="text" name="bio" class="form-control"></div>
    <div class="col-md-3"><label>Photo (jpg/png/webp)</label><input type="file" name="photo" accept="image/png,image/jpeg,image/webp" class="form-control"></div>
    <div class="col-12"><button class="btn-nttl plein" type="submit">Ajouter</button></div>
  </form>
</div>

<div class="admin-card">
  <table class="table" style="color:var(--nttl-blanc-casse); margin:0;">
    <thead><tr><th>Photo</th><th>Nom</th><th>Genre</th><th>Bio</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($artistes as $a): ?>
      <tr>
        <td>
          <?php if (!empty($a['photo'])): ?>
            <img src="<?= htmlspecialchars(NTTL_UPLOAD_WEB_ARTISTES . $a['photo']) ?>" style="width:44px;height:44px;object-fit:cover;border-radius:4px;">
          <?php else: ?>
            <span class="text-muted">—</span>
          <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($a['nom']) ?></td>
        <td class="text-muted"><?= htmlspecialchars($a['genre'] ?? '') ?></td>
        <td class="text-muted" style="max-width:280px;"><?= htmlspecialchars($a['bio'] ?? '') ?></td>
        <td>
          <a href="<?= nttl_url('/O/artiste.php') ?>?slug=<?= urlencode($a['slug']) ?>" target="_blank" class="btn-nttl" style="margin-right:6px;">Voir</a>
          <form method="post" style="display:inline;" onsubmit="return confirm('Supprimer cet artiste ?');">
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
