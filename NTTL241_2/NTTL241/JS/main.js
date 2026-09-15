/* =====================================================
   NTTL241 — Script principal
   ===================================================== */
document.addEventListener('DOMContentLoaded', function () {

  /* ---- Menu overlay plein écran (icône égaliseur) ---- */
  var toggle  = document.querySelector('.nttl-eq-toggle');
  var overlay = document.querySelector('.nttl-overlay');
  var closeBtns = document.querySelectorAll('[data-nttl-close]');

  function ouvrirMenu(){
    overlay.classList.add('ouvert');
    toggle.classList.add('actif');
    toggle.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
  }
  function fermerMenu(){
    overlay.classList.remove('ouvert');
    toggle.classList.remove('actif');
    toggle.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  }

  if (toggle && overlay) {
    toggle.addEventListener('click', function () {
      overlay.classList.contains('ouvert') ? fermerMenu() : ouvrirMenu();
    });
    closeBtns.forEach(function (btn) { btn.addEventListener('click', fermerMenu); });
    document.querySelectorAll('.nttl-overlay__menu a').forEach(function (a) {
      a.addEventListener('click', fermerMenu);
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') fermerMenu();
    });
  }

  /* ---- Navbar : légère opacité au scroll ---- */
  var nav = document.querySelector('.nttl-nav');
  if (nav) {
    window.addEventListener('scroll', function () {
      nav.style.boxShadow = window.scrollY > 8 ? '0 6px 24px rgba(0,0,0,.35)' : 'none';
    });
  }

  /* ---- Duplication du ticker pour un défilement continu et fluide ---- */
  var track = document.querySelector('.nttl-ticker__track');
  if (track) {
    track.innerHTML += track.innerHTML;
  }
});
