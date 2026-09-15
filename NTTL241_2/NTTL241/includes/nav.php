<?php
require_once __DIR__ . '/../PHP/config.php';
$ticker_actif = nttl_get_parametre('ticker_actif', '1') === '1';
$ticker_messages = $ticker_actif ? nttl_get_ticker_messages() : [];
$logo_web = nttl_get_parametre('logo', NTTL_LOGO_DEFAUT);
?>
<!-- ===================== BANDEAU ACTUALITÉ (piloté par l'admin) ===================== -->
<?php if (!empty($ticker_messages)): ?>
<div class="nttl-ticker" role="marquee" aria-label="Actualités NTTL241">
  <div class="nttl-ticker__track">
    <?php foreach ($ticker_messages as $m): ?>
      <span><?= htmlspecialchars($m['message']) ?></span>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>
<div class="nttl-ticker-wrap"></div>

<!-- ===================== NAVIGATION ===================== -->
<header class="nttl-nav">
  <div class="nttl-nav__inner">
    <a href="<?= nttl_url('/index.php') ?>" class="nttl-logo" aria-label="Accueil NTTL241">
      <img src="<?= htmlspecialchars($logo_web) ?>" alt="Logo NTTL241">
      <span>NTTL<b>241</b></span>
    </a>

    <button class="nttl-eq-toggle" aria-label="Ouvrir le menu" aria-expanded="false">
      <span></span><span></span><span></span><span></span>
    </button>

    <div class="nttl-nav__right">
      <div class="nttl-socials">
        <a href="<?= htmlspecialchars(nttl_get_parametre('lien_instagram','#')) ?>" target="_blank" rel="noopener" aria-label="Instagram">
          <svg viewBox="0 0 24 24"><path d="M12 2.2c3.2 0 3.6 0 4.85.07 3.25.15 4.77 1.69 4.92 4.92.06 1.25.07 1.6.07 4.85s0 3.6-.07 4.85c-.15 3.23-1.66 4.77-4.92 4.92-1.25.06-1.6.07-4.85.07s-3.6 0-4.85-.07c-3.26-.15-4.77-1.7-4.92-4.92-.06-1.25-.07-1.6-.07-4.85s0-3.6.07-4.85C2.38 3.96 3.9 2.42 7.15 2.27 8.4 2.21 8.8 2.2 12 2.2Zm0 3.13a6.67 6.67 0 1 0 0 13.34 6.67 6.67 0 0 0 0-13.34Zm0 11a4.33 4.33 0 1 1 0-8.66 4.33 4.33 0 0 1 0 8.66Zm6.94-11.27a1.56 1.56 0 1 1-3.12 0 1.56 1.56 0 0 1 3.12 0Z"/></svg>
        </a>
        <a href="<?= htmlspecialchars(nttl_get_parametre('lien_facebook','#')) ?>" target="_blank" rel="noopener" aria-label="Facebook">
          <svg viewBox="0 0 24 24"><path d="M13.5 21v-7.7h2.6l.4-3h-3v-1.9c0-.87.24-1.46 1.5-1.46h1.6V4.2c-.28-.04-1.23-.12-2.34-.12-2.32 0-3.9 1.4-3.9 4v2.22H7.7v3h2.66V21h3.14Z"/></svg>
        </a>
        <a href="<?= htmlspecialchars(nttl_get_parametre('lien_tiktok','#')) ?>" target="_blank" rel="noopener" aria-label="TikTok">
          <svg viewBox="0 0 24 24"><path d="M16.6 2h-3.1v13.1c0 1.5-1.2 2.7-2.7 2.7a2.7 2.7 0 0 1-2.7-2.7 2.7 2.7 0 0 1 2.7-2.7c.28 0 .55.04.8.12V9.4a5.9 5.9 0 0 0-.8-.06 5.85 5.85 0 0 0-5.85 5.85A5.85 5.85 0 0 0 10.8 21a5.85 5.85 0 0 0 5.85-5.85V8.4a7.6 7.6 0 0 0 4.35 1.37V6.65A4.5 4.5 0 0 1 16.6 2Z"/></svg>
        </a>
        <a href="<?= htmlspecialchars(nttl_get_parametre('lien_youtube','#')) ?>" target="_blank" rel="noopener" aria-label="YouTube">
          <svg viewBox="0 0 24 24"><path d="M23 12s0-3.4-.43-5a2.9 2.9 0 0 0-2-2C18.9 4.5 12 4.5 12 4.5s-6.9 0-8.57.5a2.9 2.9 0 0 0-2 2C1 8.6 1 12 1 12s0 3.4.43 5a2.9 2.9 0 0 0 2 2C5.1 19.5 12 19.5 12 19.5s6.9 0 8.57-.5a2.9 2.9 0 0 0 2-2C23 15.4 23 12 23 12ZM9.8 15.5v-7l6 3.5-6 3.5Z"/></svg>
        </a>
      </div>

      <a class="nttl-stream-btn" href="<?= htmlspecialchars(nttl_get_parametre('lien_streaming','#')) ?>" target="_blank" rel="noopener">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm4.6 14.6a.6.6 0 0 1-.83.2c-2.28-1.4-5.15-1.7-8.53-.93a.6.6 0 1 1-.27-1.17c3.7-.85 6.87-.48 9.43 1.07a.6.6 0 0 1 .2.83Zm1.22-2.72a.75.75 0 0 1-1.03.26c-2.6-1.6-6.57-2.06-9.65-1.13a.75.75 0 1 1-.43-1.44c3.53-1.07 7.9-.55 10.85 1.28a.75.75 0 0 1 .26 1.03Zm.1-2.83C14.98 9.1 9.6 8.9 6.45 9.86a.9.9 0 1 1-.52-1.72c3.62-1.1 9.55-.87 13.32 1.36a.9.9 0 1 1-.93 1.55Z"/></svg>
        Écouter
      </a>
    </div>
  </div>
</header>

<!-- ===================== OVERLAY MENU (icône égaliseur) ===================== -->
<nav class="nttl-overlay" aria-hidden="true">
  <button class="btn-nttl" data-nttl-close style="position:absolute; top:24px; right:24px;">Fermer ✕</button>
  <div>
    <ul class="nttl-overlay__menu">
      <li><a href="<?= nttl_url('/index.php') ?>">Accueil</a></li>
      <li><a href="<?= nttl_url('/index.php') ?>#apropos">Qui sommes-nous</a></li>
      <li><a href="<?= nttl_url('/index.php') ?>#services">Nos services</a></li>
      <li><a href="<?= nttl_url('/index.php') ?>#artistes">Nos artistes</a></li>
      <li><a href="<?= nttl_url('/O/actus.php') ?>">Actus</a></li>
      <li><a href="<?= nttl_url('/O/contact.php') ?>">Contact</a></li>
    </ul>
    <p class="nttl-overlay__sub">NTTL241 — News Talent The Label 241 · Libreville, Gabon</p>
  </div>
</nav>
