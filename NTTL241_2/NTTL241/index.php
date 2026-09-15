<?php
require_once __DIR__ . '/PHP/config.php';
$titre_page = "NTTL241 — News Talent The Label 241 | Label de production, Gabon";
$artistes    = nttl_get_artistes();
$partenaires = nttl_get_partenaires();
$dernieres_actus = nttl_get_actus_publiees(3);

function nttl_chunks($arr, $taille) { return array_chunk($arr, $taille); }

/** Affiche la vignette d'un artiste/partenaire : photo uploadée sinon initiales */
function nttl_vignette($nom, $photo, $dossier_web) {
    if (!empty($photo)) {
        echo '<img src="' . htmlspecialchars($dossier_web . $photo) . '" alt="' . htmlspecialchars($nom) . '" style="width:100%;height:100%;object-fit:cover;">';
    } else {
        echo htmlspecialchars(mb_substr($nom, 0, 2));
    }
}

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/nav.php';
?>

<!-- ===================== HERO ===================== -->
<section class="nttl-hero">
  <div class="nttl-hero__grid"></div>
  <div class="container">
    <div class="nttl-hero__content">
      <p class="eyebrow">℗ NTTL241 · Libreville, Gabon</p>
      <h1>News. Talent.<br>The <em>Label</em> 241.</h1>
      <p>Nous révélons, structurons et distribuons les talents musicaux gabonais.
         De la découverte d'un artiste à sa diffusion sur toutes les plateformes,
         NTTL241 accompagne chaque étape de sa carrière.</p>
      <div class="nttl-hero__cta">
        <a href="#artistes" class="btn-nttl plein">Découvrir nos artistes</a>
        <a href="<?= htmlspecialchars(nttl_get_parametre('lien_streaming','#')) ?>" target="_blank" rel="noopener" class="btn-nttl">Écouter le label</a>
      </div>
      <div class="stat-row">
        <div class="stat"><b><?= count($artistes) ?>+</b><span>Artistes accompagnés</span></div>
        <div class="stat"><b>2018</b><span>Année de création</span></div>
        <div class="stat"><b>100%</b><span>Talents gabonais</span></div>
      </div>
    </div>
  </div>
  <div class="nttl-hero__eq" aria-hidden="true">
    <span></span><span></span><span></span><span></span><span></span><span></span>
  </div>
</section>

<!-- ===================== QUI SOMMES-NOUS ===================== -->
<section class="section" id="apropos">
  <div class="container grid-2">
    <div>
      <p class="eyebrow">Qui sommes-nous</p>
      <h2 style="font-size:clamp(1.8rem,4vw,2.6rem);">Un label pensé pour faire grandir les talents.</h2>
      <p class="text-muted">NTTL241 — News Talent The Label 241 — est un label de production basé au Gabon,
         dédié à la découverte et à la professionnalisation des artistes musicaux. Notre mission : donner
         aux talents locaux les outils, le réseau et la visibilité nécessaires pour exister durablement
         dans l'industrie musicale, du Gabon jusqu'à l'international.</p>
      <p class="text-muted">Depuis nos débuts, nous avons accompagné une vingtaine d'artistes et collaboré
         avec de nombreux autres talents sur des projets ponctuels de distribution et de gestion de carrière.</p>
      <a href="<?= nttl_url('/O/contact.php') ?>" class="btn-nttl">Travailler avec nous</a>
    </div>
    <div>
      <p class="eyebrow">Nos activités</p>
      <div class="cards-3" style="grid-template-columns:1fr; margin-top:1rem;">
        <div class="service-card">
          <span class="num">01</span>
          <h3>Production musicale</h3>
          <p>Studio, direction artistique et accompagnement en création pour chaque projet du roster.</p>
        </div>
        <div class="service-card">
          <span class="num">02</span>
          <h3>Événementiel & scène</h3>
          <p>Organisation de showcases, concerts et apparitions publiques pour nos artistes.</p>
        </div>
        <div class="service-card">
          <span class="num">03</span>
          <h3>Diffusion média</h3>
          <p>Relations presse et diffusion sur les médias locaux et les plateformes de streaming.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===================== NOS SERVICES ===================== -->
<section class="section section-alt" id="services">
  <div class="container">
    <p class="eyebrow">Ce que nous proposons</p>
    <h2 style="font-size:clamp(1.8rem,4vw,2.6rem); max-width:620px;">Nos services</h2>
    <div class="cards-3">
      <div class="service-card">
        <span class="num">01</span>
        <h3>Accompagnement des artistes</h3>
        <p>Suivi personnalisé de la création à la sortie du projet : coaching, studio, direction artistique et stratégie de carrière.</p>
      </div>
      <div class="service-card">
        <span class="num">02</span>
        <h3>Management artistique</h3>
        <p>Gestion de carrière, booking, négociation de partenariats et structuration administrative des artistes.</p>
      </div>
      <div class="service-card">
        <span class="num">03</span>
        <h3>Distribution & création de profils</h3>
        <p>Mise en ligne sur toutes les plateformes de streaming et création de profils artistes optimisés (Spotify, Apple Music, YouTube...).</p>
      </div>
    </div>
  </div>
</section>

<!-- ===================== CARROUSEL ARTISTES DU LABEL ===================== -->
<section class="section" id="artistes">
  <div class="container">
    <p class="eyebrow">Le roster</p>
    <h2 style="font-size:clamp(1.8rem,4vw,2.6rem);">Nos artistes</h2>
    <p class="text-muted" style="max-width:560px;">Les talents portés au quotidien par NTTL241.</p>

    <div id="carouselArtistes" class="carousel slide nttl-carousel" data-bs-ride="carousel">
      <div class="carousel-inner">
        <?php foreach (nttl_chunks($artistes, 4) as $i => $groupe): ?>
        <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
          <div class="artiste-track">
            <?php foreach ($groupe as $a): ?>
            <a href="<?= nttl_url('/O/artiste.php') ?>?slug=<?= urlencode($a['slug']) ?>" class="artiste-card" style="color:inherit;">
              <div class="artiste-card__photo"><?php nttl_vignette($a['nom'], $a['photo'], NTTL_UPLOAD_WEB_ARTISTES); ?></div>
              <div class="artiste-card__body">
                <h4><?= htmlspecialchars($a['nom']) ?></h4>
                <span><?= htmlspecialchars($a['genre']) ?></span>
              </div>
            </a>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselArtistes" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselArtistes" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
      </button>
    </div>
  </div>
</section>

<!-- ===================== CARROUSEL ARTISTES PARTENAIRES ===================== -->
<section class="section section-alt">
  <div class="container">
    <p class="eyebrow">Ils nous ont fait confiance</p>
    <h2 style="font-size:clamp(1.8rem,4vw,2.6rem);">Artistes ayant utilisé nos services</h2>
    <p class="text-muted" style="max-width:560px;">Des collaborations ponctuelles en distribution, management et création de profils.</p>

    <div id="carouselPartenaires" class="carousel slide nttl-carousel" data-bs-ride="carousel">
      <div class="carousel-inner">
        <?php foreach (nttl_chunks($partenaires, 4) as $i => $groupe): ?>
        <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
          <div class="artiste-track">
            <?php foreach ($groupe as $a): ?>
            <div class="artiste-card">
              <div class="artiste-card__photo"><?php nttl_vignette($a['nom'], $a['photo'], NTTL_UPLOAD_WEB_PARTENAIRES); ?></div>
              <div class="artiste-card__body">
                <h4><?= htmlspecialchars($a['nom']) ?></h4>
                <span><?= htmlspecialchars($a['service']) ?></span>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselPartenaires" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselPartenaires" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
      </button>
    </div>
  </div>
</section>

<!-- ===================== ACTUS / BLOG ===================== -->
<?php if (!empty($dernieres_actus)): ?>
<section class="section">
  <div class="container">
    <div class="admin-topbar" style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:2rem;">
      <div>
        <p class="eyebrow">Blog du label</p>
        <h2 style="font-size:clamp(1.8rem,4vw,2.6rem); margin:0;">Dernières actus</h2>
      </div>
      <a href="<?= nttl_url('/O/actus.php') ?>" class="btn-nttl">Toutes les actus</a>
    </div>
    <div class="cards-3">
      <?php foreach ($dernieres_actus as $actu): ?>
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
  </div>
</section>
<?php endif; ?>

<!-- ===================== STREAMING & RÉSEAUX ===================== -->
<section class="section section-alt">
  <div class="container grid-2">
    <div>
      <p class="eyebrow">Écoutez-nous</p>
      <h2 style="font-size:clamp(1.6rem,3.4vw,2.2rem);">Retrouvez-nous sur les plateformes de streaming</h2>
      <p class="text-muted">Toute la musique NTTL241 réunie en un seul lien.</p>
      <div class="streaming-row">
        <a class="platform-pill" href="<?= htmlspecialchars(nttl_get_parametre('lien_streaming','#')) ?>" target="_blank" rel="noopener">Tous les liens (Linkfire)</a>
        <a class="platform-pill" href="<?= htmlspecialchars(nttl_get_parametre('lien_soundcloud','#')) ?>" target="_blank" rel="noopener">SoundCloud</a>
        <a class="platform-pill" href="https://open.spotify.com" target="_blank" rel="noopener">Spotify</a>
        <a class="platform-pill" href="https://music.apple.com" target="_blank" rel="noopener">Apple Music</a>
        <a class="platform-pill" href="https://www.youtube.com" target="_blank" rel="noopener">YouTube</a>
      </div>
    </div>
    <div>
      <p class="eyebrow">Suivez l'actualité</p>
      <h2 style="font-size:clamp(1.6rem,3.4vw,2.2rem);">Follow nous sur les réseaux</h2>
      <p class="text-muted">Backstage, sorties et actualités du label au quotidien.</p>
      <div class="socials-row">
        <a class="platform-pill" href="<?= htmlspecialchars(nttl_get_parametre('lien_instagram','#')) ?>" target="_blank" rel="noopener">Instagram</a>
        <a class="platform-pill" href="<?= htmlspecialchars(nttl_get_parametre('lien_facebook','#')) ?>" target="_blank" rel="noopener">Facebook</a>
        <a class="platform-pill" href="<?= htmlspecialchars(nttl_get_parametre('lien_tiktok','#')) ?>" target="_blank" rel="noopener">TikTok</a>
        <a class="platform-pill" href="<?= htmlspecialchars(nttl_get_parametre('lien_youtube','#')) ?>" target="_blank" rel="noopener">YouTube</a>
      </div>
    </div>
  </div>
</section>

<!-- ===================== BANDEAU CONTACT ===================== -->
<section class="section">
  <div class="container" style="text-align:center;">
    <p class="eyebrow" style="justify-content:center;">Un projet, une collaboration ?</p>
    <h2 style="font-size:clamp(1.8rem,4vw,2.6rem);">Parlons de votre musique.</h2>
    <a href="<?= nttl_url('/O/contact.php') ?>" class="btn-nttl plein" style="margin-top:1rem;">Contactez NTTL241</a>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
