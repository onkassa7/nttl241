# NTTL241 — News Talent The Label 241

Site vitrine du label en PHP + Bootstrap 5 + MySQL, avec espace admin complet et sécurisé.

## Structure
```
NTTL241/
├── .htaccess                → sécurité Apache (racine)
├── CSS/style.css            → design noir/jaune (identité du logo)
├── BOOTSTRAP/                → Bootstrap chargé par défaut via CDN
├── PHP/
│   ├── config.php            → connexion MySQL + sécurité (CSRF, sessions, uploads, en-têtes HTTP)
│   ├── database.sql          → schéma complet + données de départ
│   └── admin/                 → espace d'administration
│       ├── index.php          → connexion (CSRF + anti brute-force)
│       ├── dashboard.php       → vue d'ensemble
│       ├── actualite.php       → bandeau d'actualité (ticker)
│       ├── artistes.php        → roster du label (+ upload photo)
│       ├── partenaires.php     → artistes ayant utilisé nos services (+ upload photo)
│       ├── actus.php           → blog / actualités (+ upload image)
│       ├── parametres.php      → logo du site + liens réseaux sociaux/streaming
│       ├── messages.php        → messages reçus via le formulaire de contact
│       ├── includes/            → gabarit admin (accès direct bloqué)
│       └── generer-mdp.php     → utilitaire pour régénérer le mot de passe admin (à SUPPRIMER après usage)
├── IMG/
│   ├── logo.svg               → logo par défaut
│   └── uploads/                → photos/logo uploadés (exécution de scripts bloquée ici)
├── JS/main.js
├── O/                          → pages utilisateur (contact, fiche artiste, actus, mentions légales)
├── includes/                   → header / nav / footer partagés (accès direct bloqué)
└── index.php
```

## Installation

1. Créez une base MySQL et importez le schéma :
   ```
   mysql -u root -p < PHP/database.sql
   ```
2. Renseignez vos identifiants dans `PHP/config.php` (`NTTL_DB_HOST`, `NTTL_DB_NAME`, `NTTL_DB_USER`, `NTTL_DB_PASS`).
3. Le dossier `IMG/uploads/` doit être accessible en écriture (`chmod 775`).
4. Connectez-vous sur `PHP/admin/index.php` (identifiant `admin` / mot de passe `NTTL241admin`),
   **changez immédiatement le mot de passe** via `PHP/admin/generer-mdp.php`, puis **supprimez ce fichier**.
5. **Avant la mise en ligne définitive**, ouvrez `PHP/config.php` et repassez `NTTL_DEBUG` à `false`
   si vous l'aviez activé pour du débogage local (il est à `false` par défaut).

## Sécurité mise en place

- **Chemins de site auto-détectés** (`NTTL_BASE_URL`) : le site fonctionne aussi bien à la racine
  d'un domaine qu'en local dans un sous-dossier (ex. `http://localhost/NTTL241/` sous WampServer)
  — c'est ce qui causait le CSS cassé, corrigé.
- **CSRF** : tous les formulaires admin (ajout/suppression artiste, partenaire, actu, paramètres,
  bandeau d'actu) sont protégés par un jeton à usage unique. Le formulaire de contact public l'est
  aussi, avec en plus un champ piège invisible contre les robots.
- **Anti brute-force** : après 5 tentatives de connexion échouées, l'IP est bloquée 15 minutes.
- **Sessions sécurisées** : cookie `HttpOnly` + `SameSite=Lax` (+ `Secure` automatique si le site
  tourne en HTTPS), régénération de l'identifiant de session à la connexion (anti session-fixation),
  déconnexion automatique après 30 minutes d'inactivité.
- **Uploads renforcés** : type MIME vérifié, contenu vérifié comme étant une vraie image
  (`getimagesize`), taille limitée à 5 Mo, nom de fichier régénéré aléatoirement, exécution de
  scripts interdite dans le dossier d'uploads.
- **En-têtes HTTP de sécurité** envoyés sur toutes les pages : `Content-Security-Policy`,
  `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy`.
- **Requêtes SQL préparées** partout (PDO) — aucune concaténation de données utilisateur dans une requête.
- **Fichiers sensibles protégés** par `.htaccess` : `config.php`, `database.sql`, `README.md` et les
  dossiers `includes/` (public + admin) sont inaccessibles directement par URL.
- **Erreurs PHP masquées** en production (`NTTL_DEBUG = false`) pour ne jamais exposer de détails
  techniques à un visiteur.

⚠️ Ces `.htaccess` fonctionnent sur un serveur **Apache**. Si votre hébergement tourne sous
**Nginx**, il faudra transposer ces règles dans la configuration du serveur (bloquer `.sql`/`.md`,
interdire l'exécution de PHP dans `IMG/uploads/`) — dites-le-moi si c'est votre cas, je vous
donnerai la config équivalente.

## Ce que gère l'administration
- Bandeau d'actualité (activable/désactivable).
- Nos artistes / Artistes partenaires — avec upload de photo réelle.
- Actus / Blog — articles avec image, publiés ou en brouillon.
- Paramètres — logo du label, liens réseaux sociaux, lien de streaming.
- Messages reçus via le formulaire de contact.

## Pages publiques
- `/O/artiste.php?slug=...` — fiche bio détaillée d'un artiste.
- `/O/actus.php` / `/O/actu.php?slug=...` — liste et article de blog.
- `/O/contact.php` — formulaire de contact.

## Prochaine étape
On continue projet par projet — dites-moi simplement la prochaine modification à apporter et je
m'en occupe.
