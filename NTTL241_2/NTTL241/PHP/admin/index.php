<?php
require_once __DIR__ . '/../config.php';

if (nttl_est_admin_connecte()) {
    header('Location: dashboard.php');
    exit;
}

$erreur = '';
$ip = nttl_ip_client();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    nttl_exiger_csrf();

    if (nttl_login_verrouille($ip)) {
        $erreur = "Trop de tentatives échouées. Réessayez dans quelques minutes.";
    } else {
        $utilisateur = trim($_POST['utilisateur'] ?? '');
        $motdepasse  = $_POST['motdepasse'] ?? '';

        if (nttl_verifier_identifiants($utilisateur, $motdepasse)) {
            session_regenerate_id(true); // empêche la fixation de session
            $_SESSION['nttl_admin'] = true;
            $_SESSION['nttl_derniere_activite'] = time();
            nttl_reinitialiser_tentatives_login($ip);
            header('Location: dashboard.php');
            exit;
        }
        nttl_enregistrer_echec_login($ip);
        $erreur = "Identifiants incorrects.";
    }
}

$titre_admin = "Connexion — Administration NTTL241";
include __DIR__ . '/includes/admin-header.php';
?>
<div style="min-height:90vh; display:flex; align-items:center; justify-content:center; width:100%;">
  <div class="admin-card" style="width:100%; max-width:380px;">
    <p class="eyebrow">Espace admin</p>
    <h1 style="font-size:1.7rem;">Connexion</h1>
    <?php if (isset($_GET['expiree'])): ?>
      <div class="alert-nttl" style="margin-bottom:1rem;">Votre session a expiré par inactivité. Reconnectez-vous.</div>
    <?php endif; ?>
    <?php if ($erreur): ?><div class="alert-nttl" style="border-color:#ff5b5b; color:#ff9d9d; margin-bottom:1rem;"><?= htmlspecialchars($erreur) ?></div><?php endif; ?>
    <form method="post" class="form-nttl">
      <?php nttl_champ_csrf(); ?>
      <div class="mb-3">
        <label for="utilisateur">Identifiant</label>
        <input type="text" class="form-control" id="utilisateur" name="utilisateur" required autofocus autocomplete="username">
      </div>
      <div class="mb-3">
        <label for="motdepasse">Mot de passe</label>
        <input type="password" class="form-control" id="motdepasse" name="motdepasse" required autocomplete="current-password">
      </div>
      <button type="submit" class="btn-nttl plein w-100">Se connecter</button>
    </form>
    <p class="text-muted" style="font-size:.78rem; margin-top:1.2rem;">Identifiants par défaut : admin / NTTL241admin — changez le mot de passe via generer-mdp.php après import de database.sql, puis supprimez ce fichier.</p>
  </div>
</div>
<?php include __DIR__ . '/includes/admin-footer.php'; ?>
