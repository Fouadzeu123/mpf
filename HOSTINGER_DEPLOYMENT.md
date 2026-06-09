# Guide de Déploiement Hostinger - MPF

Ce guide explique pas à pas comment déployer et configurer l'application Laravel (avec Vue 3 + Inertia.js + Tailwind CSS) sur un hébergement mutualisé **Hostinger** (hPanel).

---

## 📋 Prérequis

1. **Version PHP** : Assurez-vous que votre hébergement utilise **PHP 8.2** ou supérieur (configurable via la section *Configuration PHP* du hPanel Hostinger).
2. **Base de données** : Créez une base de données MySQL dans Hostinger (*Bases de données MySQL*) et notez les informations suivantes :
   - Nom de la base de données
   - Utilisateur de la base de données
   - Mot de passe
   - Hôte (souvent `localhost` ou une IP interne fournie par Hostinger)

---

## 🛠️ Étape 1 : Préparation locale des assets (Vite)

Inertia.js nécessite que les composants Vue 3 soient compilés en Javascript natif optimisé pour la production.

1. Dans votre terminal de développement local, à la racine du projet, exécutez :
   ```bash
   npm run build
   ```
2. Cette commande génère les fichiers finaux compilés dans le dossier `public/build/`.
3. **Important** : C'est ce dossier `public/build/` compilé localement qui doit être transféré sur Hostinger.

---

## 📦 Étape 2 : Choix de la méthode de déploiement

Vous pouvez déployer l'application en utilisant l'une des deux méthodes suivantes :

### 🔒 Méthode A : Déploiement Sécurisé (Recommandé)
Cette méthode isole le code source sensible de Laravel (variables d'environnement, configurations, logs) à l'extérieur du dossier racine public d'Hostinger (`public_html`).

#### Structure finale sur le serveur :
```
/home/u123456789/                   (Dossier racine utilisateur de votre hébergement)
  ├── public_html/                  (Dossier accessible depuis le web)
  │     ├── build/                  (Dossier public/build copié ici)
  │     ├── index.php               (Fichier public/index.php copié ici et modifié)
  │     ├── symlink.php             (Fichier public/symlink.php copié ici)
  │     ├── .htaccess               (Fichier public/.htaccess d'origine copié ici)
  │     └── ...                     (Autres fichiers du dossier public/ de Laravel)
  └── bibleStory/                   (Dossier contenant tout le reste de l'application Laravel)
        ├── app/
        ├── bootstrap/
        ├── config/
        ├── database/
        ├── vendor/
        ├── .env
        └── ...
```

#### Procédure :
1. Créez un dossier nommé `bibleStory` dans la racine utilisateur de votre hébergement (au même niveau que le dossier `public_html`).
2. Uploadez-y l'intégralité des fichiers de l'application **à l'exception du dossier `public`**.
3. Copiez le contenu du dossier local `public` **directement** à l'intérieur du dossier `public_html` d'Hostinger.
4. Ouvrez le fichier `public_html/index.php` et modifiez les lignes suivantes pour pointer vers le dossier `bibleStory` parent :
   ```php
   // Ligne ~47 : Chemin vers autoload.php
   require __DIR__.'/../bibleStory/vendor/autoload.php';

   // Ligne ~61 : Chemin vers app.php
   $app = require_once __DIR__.'/../bibleStory/bootstrap/app.php';
   ```

---

### 📂 Méthode B : Déploiement Direct (.htaccess)
Cette méthode est plus rapide à mettre en place car elle permet d'uploader tout le projet tel quel dans `public_html`. Un fichier `.htaccess` gère la redirection et sécurise les répertoires.

#### Structure finale sur le serveur :
```
/home/u123456789/
  └── public_html/                  (Tout le projet est uploade ici)
        ├── .htaccess               (Le fichier .htaccess racine fourni configuré)
        ├── app/
        ├── bootstrap/
        ├── config/
        ├── public/
        │     ├── index.php
        │     ├── symlink.php
        │     └── ...
        ├── .env
        └── ...
```

#### Procédure :
1. Uploadez la totalité des fichiers du projet (y compris le dossier `public`) directement dans le dossier `public_html` d'Hostinger.
2. Le fichier `.htaccess` que nous avons créé à la racine du projet redirigera automatiquement et de façon sécurisée toutes les requêtes vers le sous-dossier `public/index.php`. Il bloque également l'accès web direct au fichier `.env` ou aux dossiers de code source (`app/`, `bootstrap/`, `vendor/`, etc.).

---

## ⚙️ Étape 3 : Configuration du fichier `.env`

Créez ou modifiez le fichier `.env` dans votre dossier de code source de l'application avec les paramètres de production d'Hostinger :

```env
APP_NAME="MPF Bible Story"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-nom-de-domaine.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1                  # Modifiez avec l'hôte de base de données Hostinger
DB_PORT=3306
DB_DATABASE=votre_bdd_hostinger
DB_USERNAME=votre_utilisateur_hostinger
DB_PASSWORD=votre_mot_de_passe_hostinger

# Laissez les autres configurations de mail/cache
```

---

## 🚀 Étape 4 : Finalisation du déploiement (symlink.php)

Pour finaliser le déploiement, vous devez exécuter les migrations de base de données et lier le dossier de stockage d'images. Si vous n'avez pas d'accès SSH (terminal) disponible sur votre offre Hostinger, vous pouvez utiliser l'utilitaire web fourni.

1. **Sécurité d'abord** : Ouvrez le fichier `public/symlink.php` (ou `public_html/symlink.php` selon la méthode choisie) et modifiez la clé d'accès secrète :
   ```php
   define('SECRET_KEY', 'votre_cle_de_securite_ultra_secrete');
   ```
2. Accédez à l'outil de déploiement via votre navigateur :
   `https://votre-nom-de-domaine.com/symlink.php?key=votre_cle_de_securite_ultra_secrete`
3. Utilisez l'interface pour effectuer les actions suivantes dans l'ordre :
   - **Exécuter les Migrations** : Crée les tables dans votre base de données Hostinger.
   - **Lancer les Seeders** : Insère les données initiales nécessaires (utilisateurs par défaut, etc.).
   - **Générer le Lien de Stockage** : Indispensable pour l'affichage correct des photos de profil, cartes de membres en PDF, etc.
   - **Vider les Caches** : Nettoie les caches de configuration de Laravel pour charger les nouvelles variables du fichier `.env` de production.

4. **Recommandation de sécurité** : Une fois le déploiement terminé avec succès, supprimez ou renommez le fichier `symlink.php` sur le serveur pour éviter tout risque d'exécution accidentelle.

---

## ⚠️ Éviter la suppression des photos de membres lors des déploiements

Sur Hostinger (ou tout autre serveur de production), les photos des membres sont téléversées et stockées dans le dossier dynamique :
`/home/u123456789/bibleStory/storage/app/public/members/`

Lors de la mise à jour ou du redéploiement de votre code, ces fichiers peuvent être accidentellement supprimés si vous écrasez les dossiers. Voici les règles d'or pour l'éviter :

### 1. Si vous déployez par Archive ZIP (File Manager Hostinger) :
- **Ne mettez jamais le dossier `storage` dans votre fichier `.zip` local** que vous uploadez. Excluez-le de l'archive avant de l'envoyer.
- Si vous uploadez le dossier `storage` vide de votre projet local et que vous l'extrayez sur Hostinger, il écrasera (et donc videra) le dossier `storage` contenant les photos de production.

### 2. Si vous utilisez Git / GitHub (Déploiement automatique) :
- Assurez-vous que votre script ou outil de déploiement (comme Git ou un webhook) n'exécute **pas** la commande `git clean -fd` sur le dossier `storage`, car les photos étant des fichiers non suivis par Git (présents dans `.gitignore`), elles seraient définitivement supprimées par cette commande.

### 3. Si vous déployez par FTP (FileZilla, Cyberduck, etc.) :
- Lors du transfert FTP, configurez votre client pour **exclure** le transfert du dossier `storage/` ou configurez-le pour ne **jamais supprimer/écraser** les fichiers du dossier `storage/app/public/members/` sur le serveur.

> [!TIP]
> **Bonne pratique de sauvegarde** :
> Avant chaque mise à jour de code en production, téléchargez et sauvegardez toujours le dossier `storage/app/public/members/` de votre serveur Hostinger vers votre ordinateur local. Ainsi, en cas d'erreur de manipulation, vous pourrez restaurer toutes les photos instantanément.
