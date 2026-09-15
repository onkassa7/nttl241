<?php
require_once __DIR__ . '/../config.php';
nttl_exiger_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    nttl_exiger_csrf();
    if (($_POST['action'] ?? '') === 'ajouter') {
        $nom = trim($_POST['nom'] ?? '');
        $service = trim($_POST['service'] ?? '');
        if ($nom !== '') {
            $photo = nttl_upload_image('photo', NTTL_UPLOAD_DIR_PARTENAIRES, 'partenaire');
            nttl_ajouter_partenaire($nom, $service ?: 'Service non précisé', $photo ?: null);
        }
    } elseif (($_POST['action'] ?? '') === 'supprimer') {
        nttl_supprimer_partenaire((int)($_POST['id'] ?? 0));
    }
    header('Location: partenaires.php');
    exit;
}

$partenaires = nttl_get_partenaires();
$titre_admin = "Artistes partenaires — Administration NTTL241";
$page_admin = 'partenaires';
include __DIR__ . '/includes/admin-header.php';
?>
<div class="admin-topbar">
  <div>
    <p class="eyebrow">Contenu du site</p>
    <h1 style="font-size:1.9rem;">Artistes ayant utilisé nos services</h1>
  </div>
</div>

<div class="admin-card" style="margin-bottom:26px;">
  <h3 style="font-size:1.1rem; text-transform:none; font-family:var(--f-body); font-weight:700;">Ajouter un artiste partenaire</h3>
  <form method="post" enctype="multipart/form-data" class="form-nttl row g-3" style="margin-top:.6rem;">
    <?php nttl_champ_csrf(); ?>
    <input type="hidden" name="action" value="ajouter">
    <div class="col-md-4"><label>Nom</label><input type="text" name="nom" class="form-control" required></div>
    <div class="col-md-4"><label>Service rendu</label><input type="text" name="service" class="form-control" placeholder="Ex : Distribution, Management ponctuel..."></div>
    <div class="col-md-4"><label>Photo (jpg/png/webp)</label><input type="file" name="photo" accept="image/png,image/jpeg,image/webp" class="form-control"></div>
    <div class="col-12"><button class="btn-nttl plein" type="submit">Ajouter</button></div>
  </form>
</div>

<div class="admin-card">
  <table class="table" style="color:var(--nttl-blanc-casse); margin:0;">
    <thead><tr><th>Photo</th><th>Nom</th><th>Service</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($partenaires as $p): ?>
      <tr>
        <td>
          <?php if (!empty($p['photo'])): ?>
            <img src="<?= htmlspecialchars(NTTL_UPLOAD_WEB_PARTENAIRES . $p['photo']) ?>" style="width:44px;height:44px;object-fit:cover;border-radius:4px;">
          <?php else: ?><span class="text-muted">—</span><?php endif; ?>
        </td>
        <td><?= htmlspecialchars($p['nom']) ?></td>
        <td class="text-muted"><?= htmlspecialchars($p['service'] ?? '') ?></td>
        <td>
          <form method="post" onsubmit="return confirm('Supprimer cet artiste ?');">
    <?php nttl_champ_csrf(); ?>
            <input type="hidden" name="action" value="supprimer">
            <input type="hidden" name="id" value="<?= $p['id'] ?>">
            <button class="btn-nttl" type="submit">Supprimer</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/includes/admin-footer.php'; ?>
