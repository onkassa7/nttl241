<?php
require_once __DIR__ . '/../config.php';
nttl_exiger_admin();

$ticker      = nttl_get_ticker_messages();
$artistes    = nttl_get_artistes();
$partenaires = nttl_get_partenaires();
$messages    = nttl_get_messages_contact();
$actus       = nttl_get_toutes_actus();
$non_lus     = count(array_filter($messages, fn($m) => empty($m['lu'])));

$titre_admin = "Tableau de bord — Administration NTTL241";
$page_admin = 'dashboard';
include __DIR__ . '/includes/admin-header.php';
?>
<div class="admin-topbar">
  <div>
    <p class="eyebrow">Administration</p>
    <h1 style="font-size:1.9rem;">Tableau de bord</h1>
  </div>
</div>

<div class="cards-3" style="margin-top:0;">
  <div class="admin-card">
    <span class="num text-jaune" style="font-family:var(--f-mono);">Messages actualité</span>
    <h3 style="margin-top:.4em;"><?= count($ticker) ?></h3>
    <p class="text-muted">Statut : <?= nttl_get_parametre('ticker_actif','1') === '1' ? 'Actif sur le site' : 'Désactivé' ?></p>
    <a href="actualite.php" class="btn-nttl" style="margin-top:.6em;">Gérer</a>
  </div>
  <div class="admin-card">
    <span class="num text-jaune" style="font-family:var(--f-mono);">Artistes du label</span>
    <h3 style="margin-top:.4em;"><?= count($artistes) ?></h3>
    <p class="text-muted">Présentés dans le carrousel « Nos artistes »</p>
    <a href="artistes.php" class="btn-nttl" style="margin-top:.6em;">Gérer</a>
  </div>
  <div class="admin-card">
    <span class="num text-jaune" style="font-family:var(--f-mono);">Artistes partenaires</span>
    <h3 style="margin-top:.4em;"><?= count($partenaires) ?></h3>
    <p class="text-muted">Ayant utilisé nos services</p>
    <a href="partenaires.php" class="btn-nttl" style="margin-top:.6em;">Gérer</a>
  </div>
  <div class="admin-card">
    <span class="num text-jaune" style="font-family:var(--f-mono);">Actus publiées</span>
    <h3 style="margin-top:.4em;"><?= count($actus) ?></h3>
    <p class="text-muted">Articles du blog NTTL241</p>
    <a href="actus.php" class="btn-nttl" style="margin-top:.6em;">Gérer</a>
  </div>
  <div class="admin-card">
    <span class="num text-jaune" style="font-family:var(--f-mono);">Messages reçus</span>
    <h3 style="margin-top:.4em;"><?= count($messages) ?> <?php if ($non_lus): ?><span class="text-jaune" style="font-size:.9rem;">(<?= $non_lus ?> non lus)</span><?php endif; ?></h3>
    <p class="text-muted">Depuis le formulaire de contact</p>
    <a href="messages.php" class="btn-nttl" style="margin-top:.6em;">Consulter</a>
  </div>
  <div class="admin-card">
    <span class="num text-jaune" style="font-family:var(--f-mono);">Paramètres</span>
    <h3 style="margin-top:.4em;">Logo & réseaux</h3>
    <p class="text-muted">Logo du site et liens réseaux sociaux / streaming</p>
    <a href="parametres.php" class="btn-nttl" style="margin-top:.6em;">Modifier</a>
  </div>
</div>
<?php include __DIR__ . '/includes/admin-footer.php'; ?>
