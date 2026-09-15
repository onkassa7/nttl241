-- =====================================================
-- NTTL241 — Schéma de base de données MySQL
-- Importez ce fichier via phpMyAdmin ou :
--   mysql -u root -p < database.sql
-- =====================================================

CREATE DATABASE IF NOT EXISTS nttl241 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE nttl241;

-- ---------- Réglages généraux du site (logo, réseaux sociaux, streaming) ----------
CREATE TABLE IF NOT EXISTS parametres (
  cle    VARCHAR(60) PRIMARY KEY,
  valeur TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO parametres (cle, valeur) VALUES
  ('logo', '/IMG/logo.svg'),
  ('lien_streaming', 'https://li.sten.to/NTTL241'),
  ('lien_soundcloud', 'https://soundcloud.com/nttl241'),
  ('lien_instagram', '#'),
  ('lien_facebook', '#'),
  ('lien_tiktok', '#'),
  ('lien_youtube', '#'),
  ('ticker_actif', '1')
ON DUPLICATE KEY UPDATE valeur = VALUES(valeur);

-- ---------- Utilisateurs admin ----------
CREATE TABLE IF NOT EXISTS admin_users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  identifiant VARCHAR(100) UNIQUE NOT NULL,
  mot_de_passe VARCHAR(255) NOT NULL,
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Identifiant : admin — Mot de passe : NTTL241admin (à changer après la 1ère connexion)
INSERT INTO admin_users (identifiant, mot_de_passe) VALUES
  ('admin', '$2b$12$9u.lIq.IBGBpI1mMev7r8.ttNUoMYPjkVz5O1MPNf.ZZSxE9w6lnu')
ON DUPLICATE KEY UPDATE identifiant = identifiant;

-- ---------- Tentatives de connexion admin (protection anti brute-force) ----------
CREATE TABLE IF NOT EXISTS tentatives_connexion (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ip VARCHAR(45) NOT NULL,
  date_tentative DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Artistes du label ----------
CREATE TABLE IF NOT EXISTS artistes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(150) NOT NULL,
  slug VARCHAR(180) NOT NULL UNIQUE,
  genre VARCHAR(150),
  bio TEXT,
  photo VARCHAR(255) DEFAULT NULL,
  ordre INT DEFAULT 0,
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO artistes (nom, slug, genre, bio, ordre) VALUES
('Big-M Monster','big-m-monster','Rap / Trap','Figure montante du roster NTTL241, à l\'univers sombre et percutant.',1),
('David Le Roi Winner','david-le-roi-winner','Afrobeat / RnB','Voix mélodique du label, entre afrobeat et sonorités urbaines.',2),
('DarkLight','darklight','Rap','Plume affûtée et flow habité, l\'un des piliers de NTTL241.',3),
('Defasto','defasto','Rap / Trap','Énergie brute et refrains accrocheurs signés NTTL241.',4),
('La Roma YS','la-roma-ys','Rap','Artiste engagé au discours affirmé, révélé par le label.',5),
('Pablito Traducteur','pablito-traducteur','Rap / Afro','Storyteller du quotidien gabonais, plume reconnaissable entre mille.',6),
('Stanboy A6','stanboy-a6','Rap / Trap','Nouvelle génération, sons taillés pour le club et la rue.',7),
('Danny Dan','danny-dan','Rap','Artiste polyvalent du roster NTTL241.',8),
('CalvinCKG11','calvinckg11','Rap / Trap','Son signature et présence scénique remarquée.',9),
('Les Ngozistes','les-ngozistes','Collectif Rap','Collectif emblématique de la saga Ngoziste, plusieurs actes à son actif.',10)
ON DUPLICATE KEY UPDATE nom = VALUES(nom);

-- ---------- Artistes ayant utilisé les services du label ----------
CREATE TABLE IF NOT EXISTS partenaires (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(150) NOT NULL,
  service VARCHAR(200),
  photo VARCHAR(255) DEFAULT NULL,
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO partenaires (nom, service) VALUES
('CalvinKlein','Distribution'),
('Diana','Distribution & Promotion'),
('Donald T KIZ','Création de profil artiste'),
('LE SWAGGANDO','Distribution'),
('Magzame','Management ponctuel'),
('Mareco','Distribution'),
('L\'inconnue Des Bas Quartiers','Création de profil artiste')
ON DUPLICATE KEY UPDATE service = VALUES(service);

-- ---------- Bandeau d'actualité (ticker) ----------
CREATE TABLE IF NOT EXISTS actualites_ticker (
  id INT AUTO_INCREMENT PRIMARY KEY,
  message VARCHAR(255) NOT NULL,
  ordre INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO actualites_ticker (message, ordre) VALUES
('Bienvenue sur le site officiel de NTTL241 — News Talent The Label 241', 1),
('Nouveau son disponible sur toutes les plateformes : écoutez-le sur li.sten.to/NTTL241', 2),
('NTTL241 accompagne aujourd\'hui plus de 20 artistes à travers le Gabon et l\'Afrique Centrale', 3);

-- ---------- Actus / Blog du label ----------
CREATE TABLE IF NOT EXISTS actus (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titre VARCHAR(220) NOT NULL,
  slug VARCHAR(240) NOT NULL UNIQUE,
  extrait VARCHAR(320),
  contenu MEDIUMTEXT,
  image VARCHAR(255) DEFAULT NULL,
  publie TINYINT(1) DEFAULT 1,
  date_publication DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO actus (titre, slug, extrait, contenu, publie) VALUES
('Bienvenue sur le nouveau site NTTL241', 'bienvenue-nouveau-site-nttl241',
 'NTTL241 lance sa nouvelle plateforme officielle pour mettre en lumière ses artistes et ses actualités.',
 'News Talent The Label 241 est fier de vous présenter son tout nouveau site officiel. Vous y retrouverez désormais l\'ensemble de nos artistes, nos actualités et toutes les infos sur nos services d\'accompagnement, de management et de distribution. Restez connectés pour ne rien manquer de la suite !',
 1);

-- ---------- Messages reçus via le formulaire de contact ----------
CREATE TABLE IF NOT EXISTS messages_contact (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(150),
  email VARCHAR(150),
  sujet VARCHAR(200),
  message TEXT,
  lu TINYINT(1) DEFAULT 0,
  date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
