<?php
require_once __DIR__ . '/../config.php';
nttl_exiger_admin();

$enregistre = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    nttl_exiger_csrf();
    foreach (['lien_streaming','lien_soundcloud','lien_instagram','lien_facebook','lien_tiktok','lien_youtube'] as $champ) {
        nttl_set_parametre($champ, trim($_POST[$champ] ?? ''));
    }
    $nouveau_logo = nttl_upload_image('logo', NTTL_UPLOAD_DIR_LOGO, 'logo');
    if ($nouveau_logo) {
        nttl_set_parametre('logo', NTTL_UPLOAD_WEB_LOGO . $nouveau_logo);
    }
    $enregistre = true;
}

$titre_admin = "Paramètres du site — Administration NTTL241";
$page_admin = 'parametres';
include __DIR__ . '/includes/admin-header.php';
?>
<div class="admin-topbar">
  <div>
    <p class="eyebrow">Réglages</p>
    <h1 style="font-size:1.9rem;">Paramètres du site</h1>
  </div>
</div>

<?php if ($enregistre): ?><div class="alert-nttl" style="margin-bottom:1.4rem;">Paramètres mis à jour.</div><?php endif; ?>

<div class="admin-card" style="max-width:640px;">
  <form method="post" enctype="multipart/form-data" class="form-nttl">
    <?php nttl_champ_csrf(); ?>

    <div class="mb-4" style="display:flex; align-items:center; gap:18px;">
      <img src="<?= htmlspecialchars(nttl_get_parametre('logo', NTTL_LOGO_DEFAUT)) ?>" alt="Logo actuel" style="height:60px; background:#000; padding:6px; border-radius:4px;">
      <div style="flex:1;">
        <label>Remplacer le logo (jpg/png/webp)</label>
        <input type="file" name="logo" accept="image/png,image/jpeg,image/webp" class="form-control">
      </div>
    </div>

    <div class="mb-3"><label>Lien streaming (li.sten.to)</label><input type="url" name="lien_streaming" class="form-control" value="<?= htmlspecialchars(nttl_get_parametre('lien_streaming')) ?>"></div>
    <div class="mb-3"><label>SoundCloud</label><input type="url" name="lien_soundcloud" class="form-control" value="<?= htmlspecialchars(nttl_get_parametre('lien_soundcloud')) ?>"></div>
    <div class="mb-3"><label>Instagram</label><input type="url" name="lien_instagram" class="form-control" value="<?= htmlspecialchars(nttl_get_parametre('lien_instagram')) ?>"></div>
    <div class="mb-3"><label>Facebook</label><input type="url" name="lien_facebook" class="form-control" value="<?= htmlspecialchars(nttl_get_parametre('lien_facebook')) ?>"></div>
    <div class="mb-3"><label>TikTok</label><input type="url" name="lien_tiktok" class="form-control" value="<?= htmlspecialchars(nttl_get_parametre('lien_tiktok')) ?>"></div>
    <div class="mb-3"><label>YouTube</label><input type="url" name="lien_youtube" class="form-control" value="<?= htmlspecialchars(nttl_get_parametre('lien_youtube')) ?>"></div>

    <button type="submit" class="btn-nttl plein">Enregistrer</button>
  </form>
</div>
<?php include __DIR__ . '/includes/admin-footer.php'; ?>
