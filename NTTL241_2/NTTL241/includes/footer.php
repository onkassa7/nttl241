<?php $logo_web = $logo_web ?? nttl_get_parametre('logo', NTTL_LOGO_DEFAUT); ?>
<footer class="nttl-footer">
  <div class="container">
    <div class="footer-grid">
      <div>
        <a href="<?= nttl_url('/index.php') ?>" class="nttl-logo" style="margin-bottom:14px;">
          <img src="<?= htmlspecialchars($logo_web) ?>" alt="Logo NTTL241" style="height:34px;">
          <span>NTTL<b>241</b></span>
        </a>
        <p class="text-muted" style="max-width:320px; font-size:.92rem; color:white;">
          News Talent The Label 241 — label de production basé au Gabon.
          Accompagnement, management et distribution pour les talents d'aujourd'hui et de demain.
        </p>
      </div>
      <div>
        <h5>Navigation</h5>
        <a href="<?= nttl_url('/index.php') ?>">Accueil</a>
        <a href="<?= nttl_url('/index.php') ?>#apropos">Qui sommes-nous</a>
        <a href="<?= nttl_url('/index.php') ?>#services">Nos services</a>
        <a href="<?= nttl_url('/index.php') ?>#artistes">Nos artistes</a>
        <a href="<?= nttl_url('/O/actus.php') ?>">Actus</a>
        <a href="<?= nttl_url('/O/contact.php') ?>">Contact</a>
      </div>
      <div>
        <h5>Suivez-nous</h5>
        <a href="<?= htmlspecialchars(nttl_get_parametre('lien_streaming','#')) ?>" target="_blank" rel="noopener">Écouter en streaming</a>
        <a href="<?= htmlspecialchars(nttl_get_parametre('lien_soundcloud','#')) ?>" target="_blank" rel="noopener">SoundCloud</a>
        <a href="<?= htmlspecialchars(nttl_get_parametre('lien_instagram','#')) ?>" target="_blank" rel="noopener">Instagram</a>
        <a href="<?= htmlspecialchars(nttl_get_parametre('lien_facebook','#')) ?>" target="_blank" rel="noopener">Facebook</a>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© <?= date('Y') ?> NTTL241 — Tous droits réservés</span>
      <span><a href="<?= nttl_url('/O/mentions-legales.php') ?>">Mentions légales</a></span>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= nttl_url('/JS/main.js') ?>"></script>
</body>
</html>
