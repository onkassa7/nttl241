<?php
require_once __DIR__ . '/../PHP/config.php';

$slug = $_GET['slug'] ?? '';
$actu = $slug ? nttl_get_actu_par_slug($slug) : null;

if (!$actu) {
    http_response_code(404);
    $titre_page = "Article introuvable — NTTL241";
    include __DIR__ . '/../includes/header.php';
    include __DIR__ . '/../includes/nav.php';
    echo '<section class="section" style="text-align:center;"><div class="container">
            <p class="eyebrow" style="justify-content:center;">Erreur 404</p>
            <h1>Article introuvable</h1>
            <p class="text-muted">Cette actualité n\'existe pas ou plus.</p>
            <a href="<?= nttl_url('/O/actus.php') ?>" class="btn-nttl plein">Retour aux actus</a>
          </div></section>';
    include __DIR__ . '/../includes/footer.php';
    exit;
}

$titre_page = htmlspecialchars($actu['titre']) . " — NTTL241";
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';
?>

<section class="section" style="padding-top:64px;">
  <div class="container" style="max-width:760px;">
    <p class="eyebrow">Blog du label · <?= htmlspecialchars(date('d.m.Y', strtotime($actu['date_publication']))) ?></p>
    <h1 style="font-size:clamp(2rem,4.5vw,3rem);"><?= htmlspecialchars($actu['titre']) ?></h1>

    <?php if (!empty($actu['image'])): ?>
      <div style="aspect-ratio:16/9; overflow:hidden; border-radius:4px; margin:2rem 0;">
        <img src="<?= htmlspecialchars(NTTL_UPLOAD_WEB_ACTUS . $actu['image']) ?>" alt="" style="width:100%;height:100%;object-fit:cover;">
      </div>
    <?php endif; ?>

    <div class="text-muted" style="font-size:1.05rem; line-height:1.8; white-space:pre-wrap;"><?= nl2br(htmlspecialchars($actu['contenu'])) ?></div>

    <a href="<?= nttl_url('/O/actus.php') ?>" class="btn-nttl" style="margin-top:2.5rem; display:inline-flex;">← Toutes les actus</a>
  </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
