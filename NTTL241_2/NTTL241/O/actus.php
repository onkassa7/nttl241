<?php
require_once __DIR__ . '/../PHP/config.php';
$titre_page = "Actus — NTTL241";
$actus = nttl_get_actus_publiees();
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';
?>

<section class="section" style="padding-top:64px;">
  <div class="container">
    <p class="eyebrow">Blog du label</p>
    <h1 style="font-size:clamp(2rem,4.5vw,3rem);">Toutes les actus</h1>
    <p class="text-muted" style="max-width:560px;">Sorties, événements et coulisses de NTTL241.</p>

    <?php if (empty($actus)): ?>
      <p class="text-muted" style="margin-top:2rem;">Aucune actualité publiée pour le moment.</p>
    <?php else: ?>
      <div class="cards-3" style="margin-top:2.5rem;">
        <?php foreach ($actus as $actu): ?>
        <a href="<?= nttl_url('/O/actu.php') ?>?slug=<?= urlencode($actu['slug']) ?>" class="service-card" style="color:inherit; display:block;">
          <?php if (!empty($actu['image'])): ?>
            <div style="aspect-ratio:16/9; overflow:hidden; margin:-34px -28px 18px; border-radius:2px 2px 0 0;">
              <img src="<?= htmlspecialchars(NTTL_UPLOAD_WEB_ACTUS . $actu['image']) ?>" alt="" style="width:100%; height:100%; object-fit:cover;">
            </div>
          <?php endif; ?>
          <span class="num" style="font-family:var(--f-mono);"><?= htmlspecialchars(date('d.m.Y', strtotime($actu['date_publication']))) ?></span>
          <h3><?= htmlspecialchars($actu['titre']) ?></h3>
          <p><?= htmlspecialchars($actu['extrait']) ?></p>
        </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
