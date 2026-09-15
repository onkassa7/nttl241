<?php
/**
 * Utilitaire ponctuel : générez ici le hash d'un nouveau mot de passe admin,
 * copiez le résultat dans la table admin_users (colonne mot_de_passe),
 * puis SUPPRIMEZ ce fichier du serveur.
 */
$nouveau_mdp = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nouveau_mdp = $_POST['mdp'] ?? '';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><title>Générateur de mot de passe — NTTL241</title>
<style>body{font-family:system-ui;background:#0B0B0C;color:#F5F3EE;padding:40px;max-width:600px;margin:auto;}
input{padding:.6em;width:100%;margin:8px 0;} button{padding:.6em 1.2em;background:#F4C512;border:0;font-weight:700;cursor:pointer;}
code{background:#1D1D20;color:#F4C512;padding:10px;display:block;word-break:break-all;margin-top:14px;border-radius:4px;}</style>
</head>
<body>
<h2>Générateur de hash mot de passe admin</h2>
<form method="post">
  <label>Nouveau mot de passe :</label>
  <input type="text" name="mdp" value="<?= htmlspecialchars($nouveau_mdp) ?>" required>
  <button type="submit">Générer le hash</button>
</form>
<?php if ($nouveau_mdp): ?>
  <p>Copiez ce hash dans <code>UPDATE admin_users SET mot_de_passe = '...' WHERE identifiant = 'admin';</code></p>
  <code><?= htmlspecialchars(password_hash($nouveau_mdp, PASSWORD_DEFAULT)) ?></code>
<?php endif; ?>
<p style="color:#8A8A90; margin-top:2rem;">⚠️ Supprimez ce fichier une fois utilisé — ne le laissez jamais en ligne.</p>
</body>
</html>
