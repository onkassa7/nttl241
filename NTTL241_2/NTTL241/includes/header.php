<?php
require_once __DIR__ . '/../PHP/config.php';
$titre_page = $titre_page ?? "NTTL241 — News Talent The Label 241";
$desc_page  = $desc_page  ?? "NTTL241 (News Talent The Label 241) — label de production basé au Gabon : accompagnement d'artistes, management artistique, distribution et création de profils.";
?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($titre_page) ?></title>
<meta name="description" content="<?= htmlspecialchars($desc_page) ?>">
<link rel="icon" type="image/svg+xml" href="<?= NTTL_LOGO_DEFAUT ?>">

<!-- Bootstrap 5 (CDN — voir /BOOTSTRAP/LISEZ-MOI.txt pour une version locale) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?= nttl_url('/CSS/style.css') ?>">
</head>
<body>
