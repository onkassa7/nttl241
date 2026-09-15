<?php
require_once __DIR__ . '/../PHP/config.php';

$slug = $_GET['slug'] ?? '';
$artiste = $slug ? nttl_get_artiste_par_slug($slug) : null;

if (!$artiste) {
    http_response_code(404);
    $titre_page = "Artiste introuvable — NTTL241";
    include __DIR__ . '/../includes/header.php';
    include __DIR__ . '/../includes/nav.php';
    echo '<section class="section" style="text-align:center;"><div class="container">
            <p class="eyebrow" style="justify-content:center;">Erreur 404</p>
            <h1>Artiste introuvable</h1>
            <p class="text-muted">Cette fiche artiste n\'existe pas ou plus.</p>
            <a href="<?= nttl_url('/index.php') ?>#artistes" class="btn-nttl plein">Retour aux artistes</a>
          </div></section>';
    include __DIR__ . '/../includes/footer.php';
    exit;
}

$titre_page = htmlspecialchars($artiste['nom']) . " — NTTL241";
$tous_les_artistes = nttl_get_artistes();
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';
?>

<section class="section" style="padding-top:64px;">
  <div class="container grid-2">
    <div>
      <div class="artiste-card__photo" style="aspect-ratio:1/1; border-radius:4px; font-size:4rem;">
        <?php if (!empty($artiste['photo'])): ?>
          <img src="<?= htmlspecialchars(NTTL_UPLOAD_WEB_ARTISTES . $artiste['photo']) ?>" alt="<?= htmlspecialchars($artiste['nom']) ?>" style="width:100%;height:100%;object-fit:cover;">
        <?php else: ?>
          <?= htmlspecialchars(mb_substr($artiste['nom'], 0, 2)) ?>
        <?php endif; ?>
      </div>
    </div>
    <div>
      <p class="eyebrow">Artiste NTTL241</p>
      <h1 style="font-size:clamp(2rem,4.5vw,3rem);"><?= htmlspecialchars($artiste['nom']) ?></h1>
      <?php if (!empty($artiste['genre'])): ?>
        <p class="text-jaune" style="font-family:var(--f-mono); text-transform:uppercase; letter-spacing:.06em; font-size:.85rem;"><?= htmlspecialchars($artiste['genre']) ?></p>
      <?php endif; ?>
      <p class="text-muted" style="font-size:1.05rem; margin-top:1.4rem;">
        <?= nl2br(htmlspecialchars($artiste['bio'] ?: "Biographie à venir.")) ?>
      </p>
      <div style="display:flex; gap:14px; margin-top:2rem; flex-wrap:wrap;">
        <a href="<?= htmlspecialchars(nttl_get_parametre('lien_streaming','#')) ?>" target="_blank" rel="noopener" class="btn-nttl plein">Écouter sur NTTL241</a>
        <a href="<?= nttl_url('/index.php') ?>#artistes" class="btn-nttl">← Tous les artistes</a>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
