<?php
/**
 * NTTL241 — Configuration globale & sécurité
 * -------------------------------------------------
 * Connexion MySQL + fonctions d'accès aux données + fonctions de
 * sécurité (CSRF, anti brute-force, session, uploads) utilisées par
 * tout le site (pages publiques + espace admin).
 */

// Empêche l'accès direct à ce fichier par URL (il ne doit être qu'inclus)
if (basename($_SERVER['SCRIPT_FILENAME'] ?? '') === basename(__FILE__)) {
    http_response_code(403);
    exit('Accès direct interdit.');
}

// ---------------------------------------------------
// Mode debug — mettre à false avant la mise en ligne définitive.
// En debug : les erreurs PHP s'affichent. En production : elles sont
// journalisées mais jamais montrées au visiteur (évite de fuiter des
// infos sensibles comme les chemins serveur ou les requêtes SQL).
// ---------------------------------------------------
define('NTTL_DEBUG', false);
if (NTTL_DEBUG) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    error_reporting(E_ALL);
}

// ---------------------------------------------------
// Session sécurisée
// ---------------------------------------------------
if (session_status() === PHP_SESSION_NONE) {
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? '') == 443;
    ini_set('session.use_strict_mode', '1');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $https,   // cookie envoyé uniquement en HTTPS si le site est servi en HTTPS
        'httponly' => true,     // inaccessible en JavaScript (protection XSS sur la session)
        'samesite' => 'Lax',
    ]);
    session_start();
}

define('NTTL_ROOT', dirname(__DIR__));

// ---------------------------------------------------
// Détection automatique du chemin de base du site.
// Corrige l'affichage (CSS/JS/images cassés) lorsque le projet n'est
// pas à la racine du domaine — cas typique en local avec WampServer :
// http://localhost/NTTL241/  →  base = "/NTTL241"
// Fonctionne aussi automatiquement une fois en ligne à la racine.
// ---------------------------------------------------
function nttl_detecter_base_url() {
    $doc_root = $_SERVER['DOCUMENT_ROOT'] ?? '';
    if (!$doc_root) return '';
    $doc_root_reel = realpath($doc_root);
    $app_root_reel = realpath(NTTL_ROOT);
    if (!$doc_root_reel || !$app_root_reel) return '';
    $doc_root_reel = str_replace('\\', '/', $doc_root_reel);
    $app_root_reel = str_replace('\\', '/', $app_root_reel);
    if (strpos($app_root_reel, $doc_root_reel) === 0) {
        return rtrim(substr($app_root_reel, strlen($doc_root_reel)), '/');
    }
    return '';
}
define('NTTL_BASE_URL', nttl_detecter_base_url());

/** Construit une URL interne cohérente avec l'emplacement réel du site */
function nttl_url($chemin) {
    return NTTL_BASE_URL . $chemin;
}

// ---------------------------------------------------
// Identifiants de connexion à la base de données
// À adapter à votre hébergement avant mise en ligne.
// ---------------------------------------------------
define('NTTL_DB_HOST', 'localhost');
define('NTTL_DB_NAME', 'nttl241');
define('NTTL_DB_USER', 'root');
define('NTTL_DB_PASS', '');

// Dossiers d'upload (chemin disque + chemin web, base incluse)
define('NTTL_UPLOAD_DIR_ARTISTES',    NTTL_ROOT . '/IMG/uploads/artistes/');
define('NTTL_UPLOAD_DIR_PARTENAIRES', NTTL_ROOT . '/IMG/uploads/partenaires/');
define('NTTL_UPLOAD_DIR_ACTUS',       NTTL_ROOT . '/IMG/uploads/actus/');
define('NTTL_UPLOAD_DIR_LOGO',        NTTL_ROOT . '/IMG/uploads/logo/');
define('NTTL_UPLOAD_WEB_ARTISTES',    nttl_url('/IMG/uploads/artistes/'));
define('NTTL_UPLOAD_WEB_PARTENAIRES', nttl_url('/IMG/uploads/partenaires/'));
define('NTTL_UPLOAD_WEB_ACTUS',       nttl_url('/IMG/uploads/actus/'));
define('NTTL_UPLOAD_WEB_LOGO',        nttl_url('/IMG/uploads/logo/'));
define('NTTL_LOGO_DEFAUT',            nttl_url('/IMG/logo.svg'));

// ---------------------------------------------------
// En-têtes de sécurité HTTP (envoyés sur toutes les pages)
// ---------------------------------------------------
if (!headers_sent()) {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
    header("Content-Security-Policy: default-src 'self'; " .
           "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; " .
           "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net; " .
           "font-src 'self' https://fonts.gstatic.com; " .
           "img-src 'self' data:; " .
           "connect-src 'self'; frame-ancestors 'self'; base-uri 'self'; form-action 'self'");
}

/**
 * Connexion PDO (singleton). En cas d'échec, on affiche un message
 * générique plutôt qu'une erreur MySQL brute (ne fuite aucune info
 * technique côté visiteur).
 */
function nttl_pdo() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                'mysql:host=' . NTTL_DB_HOST . ';dbname=' . NTTL_DB_NAME . ';charset=utf8mb4',
                NTTL_DB_USER,
                NTTL_DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
            );
        } catch (PDOException $e) {
            if (NTTL_DEBUG) { error_log($e->getMessage()); }
            http_response_code(500);
            die('<div style="font-family:sans-serif;background:#0B0B0C;color:#F4C512;padding:40px;">
                 <h2>Connexion à la base de données impossible</h2>
                 <p style="color:#F5F3EE;">Vérifiez les identifiants dans <code>PHP/config.php</code> et assurez-vous
                 d\'avoir importé <code>PHP/database.sql</code>.</p></div>');
        }
    }
    return $pdo;
}

// ---------------------------------------------------
// Paramètres généraux du site (logo, réseaux, streaming)
// ---------------------------------------------------
function nttl_get_parametre($cle, $defaut = '') {
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        foreach (nttl_pdo()->query('SELECT cle, valeur FROM parametres') as $ligne) {
            $cache[$ligne['cle']] = $ligne['valeur'];
        }
    }
    return $cache[$cle] ?? $defaut;
}

function nttl_set_parametre($cle, $valeur) {
    $stmt = nttl_pdo()->prepare('INSERT INTO parametres (cle, valeur) VALUES (:c, :v) ON DUPLICATE KEY UPDATE valeur = :v2');
    return $stmt->execute(['c' => $cle, 'v' => $valeur, 'v2' => $valeur]);
}

// ---------------------------------------------------
// CSRF — protège tous les formulaires admin contre les requêtes forgées
// ---------------------------------------------------
function nttl_jeton_csrf() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/** À placer dans chaque <form method="post"> admin */
function nttl_champ_csrf() {
    echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(nttl_jeton_csrf()) . '">';
}

/** Retourne true si le jeton reçu est valide */
function nttl_csrf_valide() {
    $recu = $_POST['csrf_token'] ?? '';
    $attendu = $_SESSION['csrf_token'] ?? '';
    return $recu !== '' && $attendu !== '' && hash_equals($attendu, $recu);
}

/** À appeler en tout début de traitement d'un POST admin : coupe la requête si le jeton est invalide */
function nttl_exiger_csrf() {
    if (!nttl_csrf_valide()) {
        http_response_code(400);
        die('<div style="font-family:sans-serif;background:#0B0B0C;color:#F4C512;padding:40px;">
             <h2>Requête invalide</h2>
             <p style="color:#F5F3EE;">Le jeton de sécurité a expiré ou est invalide. Rechargez la page et réessayez.</p></div>');
    }
}

// ---------------------------------------------------
// Authentification admin + protection anti brute-force
// ---------------------------------------------------
function nttl_verifier_identifiants($identifiant, $motdepasse) {
    $stmt = nttl_pdo()->prepare('SELECT * FROM admin_users WHERE identifiant = ? LIMIT 1');
    $stmt->execute([$identifiant]);
    $u = $stmt->fetch();
    return ($u && password_verify($motdepasse, $u['mot_de_passe']));
}

function nttl_ip_client() {
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

/** Nombre de tentatives échouées pour cette IP dans les 15 dernières minutes */
function nttl_tentatives_recentes($ip) {
    $stmt = nttl_pdo()->prepare("SELECT COUNT(*) AS n FROM tentatives_connexion WHERE ip = ? AND date_tentative > (NOW() - INTERVAL 15 MINUTE)");
    $stmt->execute([$ip]);
    return (int)($stmt->fetch()['n'] ?? 0);
}

function nttl_login_verrouille($ip) {
    return nttl_tentatives_recentes($ip) >= 5;
}

function nttl_enregistrer_echec_login($ip) {
    $stmt = nttl_pdo()->prepare('INSERT INTO tentatives_connexion (ip) VALUES (?)');
    $stmt->execute([$ip]);
    // Purge légère des anciennes tentatives (> 1h) pour ne pas faire grossir la table indéfiniment
    nttl_pdo()->exec("DELETE FROM tentatives_connexion WHERE date_tentative < (NOW() - INTERVAL 1 HOUR)");
}

function nttl_reinitialiser_tentatives_login($ip) {
    $stmt = nttl_pdo()->prepare('DELETE FROM tentatives_connexion WHERE ip = ?');
    $stmt->execute([$ip]);
}

function nttl_est_admin_connecte() {
    if (!isset($_SESSION['nttl_admin']) || $_SESSION['nttl_admin'] !== true) {
        return false;
    }
    // Expiration de session par inactivité (30 minutes)
    $inactif_depuis = time() - ($_SESSION['nttl_derniere_activite'] ?? 0);
    if ($inactif_depuis > 1800) {
        session_unset();
        session_destroy();
        return false;
    }
    $_SESSION['nttl_derniere_activite'] = time();
    return true;
}

function nttl_exiger_admin() {
    if (!nttl_est_admin_connecte()) {
        header('Location: index.php?expiree=1');
        exit;
    }
}

// ---------------------------------------------------
// Utilitaires
// ---------------------------------------------------
function nttl_slugify($texte) {
    $texte = iconv('UTF-8', 'ASCII//TRANSLIT', $texte);
    $texte = strtolower($texte);
    $texte = preg_replace('/[^a-z0-9]+/', '-', $texte);
    return trim($texte, '-');
}

/**
 * Gère l'upload d'une image en toute sécurité :
 * - vérifie le type MIME réel ET que le fichier est une vraie image décodable
 * - limite la taille
 * - génère un nom de fichier aléatoire (jamais le nom d'origine, pour éviter
 *   tout chemin/traversal ou script déguisé)
 * Retourne le nom de fichier généré, ou false en cas d'échec/absence de fichier.
 */
function nttl_upload_image($champ, $dossier_disque, $prefixe = 'img') {
    if (empty($_FILES[$champ]) || $_FILES[$champ]['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    $fichier = $_FILES[$champ];

    if ($fichier['size'] > 5 * 1024 * 1024) return false; // 5 Mo max

    $autorises = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    $type = mime_content_type($fichier['tmp_name']);
    if (!isset($autorises[$type])) return false;

    // Vérifie que le fichier est réellement décodable comme image (bloque les faux-positifs MIME)
    if (@getimagesize($fichier['tmp_name']) === false) return false;

    $nom = $prefixe . '-' . bin2hex(random_bytes(8)) . '.' . $autorises[$type];
    if (!is_dir($dossier_disque)) mkdir($dossier_disque, 0775, true);
    if (move_uploaded_file($fichier['tmp_name'], $dossier_disque . $nom)) {
        return $nom;
    }
    return false;
}

// ---------------------------------------------------
// Artistes du label
// ---------------------------------------------------
function nttl_get_artistes() {
    return nttl_pdo()->query('SELECT * FROM artistes ORDER BY ordre ASC, id ASC')->fetchAll();
}

function nttl_get_artiste_par_slug($slug) {
    $stmt = nttl_pdo()->prepare('SELECT * FROM artistes WHERE slug = ? LIMIT 1');
    $stmt->execute([$slug]);
    return $stmt->fetch() ?: null;
}

function nttl_ajouter_artiste($nom, $genre, $bio, $photo = null) {
    $slug = nttl_slugify($nom) . '-' . substr(bin2hex(random_bytes(3)), 0, 5);
    $stmt = nttl_pdo()->prepare('INSERT INTO artistes (nom, slug, genre, bio, photo) VALUES (?,?,?,?,?)');
    return $stmt->execute([$nom, $slug, $genre, $bio, $photo]);
}

function nttl_supprimer_artiste($id) {
    $stmt = nttl_pdo()->prepare('DELETE FROM artistes WHERE id = ?');
    return $stmt->execute([$id]);
}

// ---------------------------------------------------
// Artistes partenaires (ayant utilisé nos services)
// ---------------------------------------------------
function nttl_get_partenaires() {
    return nttl_pdo()->query('SELECT * FROM partenaires ORDER BY id ASC')->fetchAll();
}

function nttl_ajouter_partenaire($nom, $service, $photo = null) {
    $stmt = nttl_pdo()->prepare('INSERT INTO partenaires (nom, service, photo) VALUES (?,?,?)');
    return $stmt->execute([$nom, $service, $photo]);
}

function nttl_supprimer_partenaire($id) {
    $stmt = nttl_pdo()->prepare('DELETE FROM partenaires WHERE id = ?');
    return $stmt->execute([$id]);
}

// ---------------------------------------------------
// Bandeau d'actualité (ticker)
// ---------------------------------------------------
function nttl_get_ticker_messages() {
    return nttl_pdo()->query('SELECT * FROM actualites_ticker ORDER BY ordre ASC, id ASC')->fetchAll();
}

function nttl_remplacer_ticker_messages(array $messages) {
    $pdo = nttl_pdo();
    $pdo->exec('DELETE FROM actualites_ticker');
    $stmt = $pdo->prepare('INSERT INTO actualites_ticker (message, ordre) VALUES (?,?)');
    foreach ($messages as $i => $m) {
        $m = trim($m);
        if ($m !== '') $stmt->execute([$m, $i]);
    }
}

// ---------------------------------------------------
// Actus / Blog
// ---------------------------------------------------
function nttl_get_actus_publiees($limite = null) {
    $sql = 'SELECT * FROM actus WHERE publie = 1 ORDER BY date_publication DESC';
    if ($limite) $sql .= ' LIMIT ' . (int)$limite;
    return nttl_pdo()->query($sql)->fetchAll();
}

function nttl_get_toutes_actus() {
    return nttl_pdo()->query('SELECT * FROM actus ORDER BY date_publication DESC')->fetchAll();
}

function nttl_get_actu_par_slug($slug) {
    $stmt = nttl_pdo()->prepare('SELECT * FROM actus WHERE slug = ? AND publie = 1 LIMIT 1');
    $stmt->execute([$slug]);
    return $stmt->fetch() ?: null;
}

function nttl_ajouter_actu($titre, $extrait, $contenu, $image = null, $publie = 1) {
    $slug = nttl_slugify($titre) . '-' . substr(uniqid(), -5);
    $stmt = nttl_pdo()->prepare('INSERT INTO actus (titre, slug, extrait, contenu, image, publie) VALUES (?,?,?,?,?,?)');
    return $stmt->execute([$titre, $slug, $extrait, $contenu, $image, $publie]);
}

function nttl_supprimer_actu($id) {
    $stmt = nttl_pdo()->prepare('DELETE FROM actus WHERE id = ?');
    return $stmt->execute([$id]);
}

// ---------------------------------------------------
// Messages de contact
// ---------------------------------------------------
function nttl_ajouter_message_contact($nom, $email, $sujet, $message) {
    $stmt = nttl_pdo()->prepare('INSERT INTO messages_contact (nom, email, sujet, message) VALUES (?,?,?,?)');
    return $stmt->execute([$nom, $email, $sujet, $message]);
}

function nttl_get_messages_contact() {
    return nttl_pdo()->query('SELECT * FROM messages_contact ORDER BY date_envoi DESC')->fetchAll();
}

function nttl_supprimer_message_contact($id) {
    $stmt = nttl_pdo()->prepare('DELETE FROM messages_contact WHERE id = ?');
    return $stmt->execute([$id]);
}
