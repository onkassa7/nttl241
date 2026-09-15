<?php
require_once __DIR__ . '/../PHP/config.php';
$titre_page = "Contact — NTTL241";

$succes = false;
$erreurs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom     = trim($_POST['nom'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $sujet   = trim($_POST['sujet'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Piège à robots : ce champ doit rester vide (masqué en CSS pour les humains)
    $piege = trim($_POST['site_web'] ?? '');

    if (!nttl_csrf_valide()) $erreurs[] = "Formulaire expiré, merci de réessayer.";
    if ($nom === '')     $erreurs[] = "Le nom est obligatoire.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erreurs[] = "Merci de renseigner un email valide.";
    if ($message === '') $erreurs[] = "Le message ne peut pas être vide.";

    if ($piege !== '') {
        // Un robot a rempli le champ piège : on affiche un succès factice sans rien enregistrer
        $succes = true;
    } elseif (empty($erreurs)) {
        nttl_ajouter_message_contact($nom, $email, $sujet, $message);
        $succes = true;
    }
}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';
?>

<section class="section" style="padding-top:64px;">
  <div class="container grid-2">
    <div>
      <p class="eyebrow">Contact</p>
      <h1 style="font-size:clamp(2rem,4.5vw,3.2rem);">Parlons de votre projet.</h1>
      <p class="text-muted" style="max-width:480px;">Artiste, partenaire média ou simple curieux : écrivez-nous,
         l'équipe NTTL241 vous répond au plus vite.</p>

      <div style="margin-top:2.4rem;">
        <p class="eyebrow">Où nous trouver</p>
        <p class="text-muted">Libreville, Gabon</p>
        <p class="eyebrow" style="margin-top:1.6rem;">Streaming</p>
        <a class="platform-pill" href="<?= htmlspecialchars(nttl_get_parametre('lien_streaming','#')) ?>" target="_blank" rel="noopener" style="display:inline-flex;">Écouter NTTL241</a>
      </div>
    </div>

    <div>
      <?php if ($succes): ?>
        <div class="alert-nttl" role="alert">Merci ! Votre message a bien été envoyé. Nous revenons vers vous rapidement.</div>
      <?php else: ?>
        <?php if (!empty($erreurs)): ?>
          <div class="alert-nttl" role="alert" style="border-color:#ff5b5b; color:#ff9d9d;">
            <?php foreach ($erreurs as $e): ?><div><?= htmlspecialchars($e) ?></div><?php endforeach; ?>
          </div>
        <?php endif; ?>

        <form class="form-nttl" method="post" action="contact.php" novalidate>
          <?php nttl_champ_csrf(); ?>
          <div style="position:absolute; left:-9999px;" aria-hidden="true">
            <label for="site_web">Laissez ce champ vide</label>
            <input type="text" id="site_web" name="site_web" tabindex="-1" autocomplete="off">
          </div>
          <div class="mb-3">
            <label for="nom">Nom complet</label>
            <input type="text" class="form-control" id="nom" name="nom" required value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
          </div>
          <div class="mb-3">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
          </div>
          <div class="mb-3">
            <label for="sujet">Sujet</label>
            <input type="text" class="form-control" id="sujet" name="sujet" value="<?= htmlspecialchars($_POST['sujet'] ?? '') ?>">
          </div>
          <div class="mb-3">
            <label for="message">Message</label>
            <textarea class="form-control" id="message" name="message" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
          </div>
          <button type="submit" class="btn-nttl plein">Envoyer le message</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
