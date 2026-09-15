<?php
require_once __DIR__ . '/../../config.php';
$page_admin = $page_admin ?? '';
?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($titre_admin ?? 'Administration NTTL241') ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?= nttl_url('/CSS/style.css') ?>">
<style>
  body{ display:flex; min-height:100vh; }
  .admin-side{ width:250px; background:var(--nttl-noir-carbone); border-right:1px solid var(--nttl-ligne); padding:26px 18px; flex-shrink:0; }
  .admin-side a{ display:block; padding:.7em .8em; border-radius:4px; font-size:.92rem; color:var(--nttl-gris); margin-bottom:4px; }
  .admin-side a:hover, .admin-side a.actif{ background:var(--nttl-noir-carte); color:var(--nttl-jaune); }
  .admin-main{ flex:1; padding:36px 40px; }
  .admin-topbar{ display:flex; justify-content:space-between; align-items:center; margin-bottom:28px; flex-wrap:wrap; gap:12px; }
  .admin-card{ background:var(--nttl-noir-carte); border:1px solid var(--nttl-ligne); border-radius:6px; padding:26px; }
  @media (max-width:768px){ body{ flex-direction:column; } .admin-side{ width:100%; display:flex; overflow-x:auto; gap:4px; } }
</style>
</head>
<body>
  <?php if (nttl_est_admin_connecte()): ?>
  <aside class="admin-side">
    <a href="<?= nttl_url('/index.php') ?>" class="nttl-logo" style="margin-bottom:22px;">
      <img src="<?= htmlspecialchars(nttl_est_admin_connecte() ? nttl_get_parametre('logo', NTTL_LOGO_DEFAUT) : NTTL_LOGO_DEFAUT) ?>" alt="" style="height:32px;"><span>NTTL<b>241</b></span>
    </a>
    <a href="dashboard.php" class="<?= $page_admin==='dashboard'?'actif':'' ?>">Tableau de bord</a>
    <a href="actualite.php" class="<?= $page_admin==='actualite'?'actif':'' ?>">Bandeau d'actualité</a>
    <a href="artistes.php" class="<?= $page_admin==='artistes'?'actif':'' ?>">Nos artistes</a>
    <a href="partenaires.php" class="<?= $page_admin==='partenaires'?'actif':'' ?>">Artistes partenaires</a>
    <a href="actus.php" class="<?= $page_admin==='actus'?'actif':'' ?>">Actus / Blog</a>
    <a href="messages.php" class="<?= $page_admin==='messages'?'actif':'' ?>">Messages reçus</a>
    <a href="parametres.php" class="<?= $page_admin==='parametres'?'actif':'' ?>">Paramètres</a>
    <hr style="border-color:var(--nttl-ligne);">
    <a href="<?= nttl_url('/index.php') ?>" target="_blank">Voir le site ↗</a>
    <a href="logout.php">Se déconnecter</a>
  </aside>
  <?php endif; ?>
  <main class="admin-main">
