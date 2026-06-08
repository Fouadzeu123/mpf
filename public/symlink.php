<?php
/**
 * MPF - Hostinger Deployment Utility Tool
 * This script helps automate common Laravel artisan tasks (migrations, symlinks, cache clear) on shared hosting.
 * 
 * SECURITY NOTE: Change the secret key below before deploying to production!
 */

define('SECRET_KEY', 'mpf_deploy_key_2026');

// Auto-detect Laravel installation path
function findLaravelPath() {
    $candidates = [
        __DIR__ . '/..',                            // Method B (everything inside public_html)
        __DIR__ . '/../bibleStory',                 // Method A (Laravel in 'bibleStory' subfolder)
        __DIR__ . '/../mpf-backend',                // Method A (Laravel in 'mpf-backend' subfolder)
        dirname(__DIR__) . '/bibleStory',           // Method A (Sibling to public_html)
        dirname(__DIR__) . '/mpf-backend',          // Method A (Sibling to public_html)
        dirname(__DIR__) . '/project',              // Method A (Sibling to public_html)
    ];

    foreach ($candidates as $path) {
        if (file_exists($path . '/bootstrap/app.php') && file_exists($path . '/vendor/autoload.php')) {
            return realpath($path);
        }
    }
    return null;
}

$laravelPath = findLaravelPath();
$key = $_GET['key'] ?? '';
$action = $_GET['action'] ?? '';
$output = '';
$status = 'info';

if ($key !== SECRET_KEY) {
    http_response_code(403);
    $output = "Erreur : Clé d'accès secrète invalide ou manquante. Veuillez passer la clé correcte via ?key=VOTRE_CLE";
    $status = 'error';
} elseif (!$laravelPath) {
    $output = "Erreur : Impossible de localiser le dossier racine de Laravel. Assurez-vous que le dossier contenant bootstrap/app.php est placé correctement.";
    $status = 'error';
} elseif ($action) {
    try {
        // Bootstrap Laravel
        require $laravelPath . '/vendor/autoload.php';
        $app = require_once $laravelPath . '/bootstrap/app.php';
        
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();

        switch ($action) {
            case 'symlink':
                // Custom symlink creation to handle Hostinger-specific symlink setups
                $publicPath = __DIR__;
                $storagePath = $laravelPath . '/storage/app/public';
                $linkPath = $publicPath . '/storage';

                if (file_exists($linkPath)) {
                    if (is_link($linkPath)) {
                        unlink($linkPath);
                        $output .= "Ancien lien symbolique supprimé.\n";
                    } else {
                        // It's a directory, rename it as backup
                        rename($linkPath, $linkPath . '_backup_' . time());
                        $output .= "Dossier existant renommé en backup.\n";
                    }
                }

                if (symlink($storagePath, $linkPath)) {
                    $output .= "Lien symbolique créé avec succès de : \n{$storagePath} \nvers\n{$linkPath}\n";
                    $status = 'success';
                } else {
                    $output .= "Échec de création du lien symbolique via php symlink(). Tentative avec Artisan...\n";
                    $exitCode = Illuminate\Support\Facades\Artisan::call('storage:link');
                    $output .= Illuminate\Support\Facades\Artisan::output();
                    $status = $exitCode === 0 ? 'success' : 'error';
                }
                break;

            case 'migrate':
                $exitCode = Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                $output .= Illuminate\Support\Facades\Artisan::output();
                $status = $exitCode === 0 ? 'success' : 'error';
                break;

            case 'seed':
                $exitCode = Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
                $output .= Illuminate\Support\Facades\Artisan::output();
                $status = $exitCode === 0 ? 'success' : 'error';
                break;

            case 'clear-cache':
                $exitCode = Illuminate\Support\Facades\Artisan::call('optimize:clear');
                $output .= Illuminate\Support\Facades\Artisan::output();
                $status = $exitCode === 0 ? 'success' : 'error';
                break;

            default:
                $output = "Action non reconnue.";
                $status = 'error';
        }
    } catch (\Throwable $e) {
        $output = "Exception levée lors de l'exécution de l'action : " . $e->getMessage() . "\n\nTrace :\n" . $e->getTraceAsString();
        $status = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Console de Déploiement Hostinger - MPF</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0b1329;
            --card-bg: #111d35;
            --text-color: #f1f5f9;
            --accent-blue: #2563eb;
            --accent-gold: #d97706;
            --accent-gold-hover: #b45309;
            --border-color: #1e293b;
            --success: #10b981;
            --error: #ef4444;
            --warning: #f59e0b;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            box-sizing: border-box;
        }

        .container {
            width: 100%;
            max-width: 800px;
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.3);
            padding: 30px;
            box-sizing: border-box;
        }

        h1 {
            font-size: 24px;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 8px;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        h1 span {
            color: var(--accent-gold);
        }

        .subtitle {
            font-size: 14px;
            color: #94a3b8;
            margin-bottom: 24px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 16px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 24px;
            line-height: 1.5;
        }

        .alert-error {
            background-color: rgba(239, 68, 68, 0.1);
            border: 1px solid var(--error);
            color: #fca5a5;
        }

        .alert-success {
            background-color: rgba(16, 185, 129, 0.1);
            border: 1px solid var(--success);
            color: #86efac;
        }

        .alert-info {
            background-color: rgba(37, 99, 235, 0.1);
            border: 1px solid var(--accent-blue);
            color: #93c5fd;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 24px;
            background: rgba(0, 0, 0, 0.2);
            padding: 16px;
            border-radius: 8px;
            font-size: 14px;
            border: 1px solid var(--border-color);
        }

        .info-item strong {
            display: block;
            color: #94a3b8;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .info-item span {
            font-family: 'JetBrains Mono', monospace;
            color: #e2e8f0;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 24px;
        }

        @media (max-width: 600px) {
            .actions-grid, .info-grid {
                grid-template-columns: 1fr;
            }
        }

        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px 16px;
            background-color: var(--border-color);
            border: 1px solid transparent;
            color: var(--text-color);
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            text-align: center;
        }

        .btn:hover {
            background-color: #334155;
        }

        .btn-gold {
            background-color: var(--accent-gold);
            color: #ffffff;
        }

        .btn-gold:hover {
            background-color: var(--accent-gold-hover);
        }

        .console {
            margin-top: 24px;
        }

        .console h3 {
            font-size: 14px;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 8px;
        }

        .console-box {
            background-color: #030712;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 16px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            line-height: 1.6;
            color: #38bdf8;
            white-space: pre-wrap;
            overflow-x: auto;
            max-height: 350px;
        }

        .footer {
            margin-top: 24px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
        }
        
        .footer a {
            color: var(--accent-gold);
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Console de Déploiement <span>MPF</span></h1>
        <div class="subtitle">Outil utilitaire pour l'hébergement partagé Hostinger</div>

        <?php if ($key !== SECRET_KEY): ?>
            <div class="alert alert-error">
                <strong>Accès non autorisé :</strong> La clé secrète passée en paramètre est invalide ou absente. Pour utiliser cet outil, ajoutez <code>?key=VOTRE_CLE</code> à l'URL.
            </div>
        <?php else: ?>
            <div class="alert alert-success">
                <strong>Authentifié avec succès !</strong> Vous pouvez à présent lancer des commandes d'administration.
            </div>
        <?php endif; ?>

        <div class="info-grid">
            <div class="info-item">
                <strong>Chemin Laravel Détecté</strong>
                <span><?php echo $laravelPath ? htmlspecialchars($laravelPath) : 'Non détecté'; ?></span>
            </div>
            <div class="info-item">
                <strong>Chemin Racine Web (Public)</strong>
                <span><?php echo htmlspecialchars(__DIR__); ?></span>
            </div>
        </div>

        <?php if ($key === SECRET_KEY && $laravelPath): ?>
            <div class="actions-grid">
                <a href="?key=<?php echo htmlspecialchars($key); ?>&action=migrate" class="btn btn-gold">
                    Exécuter les Migrations
                </a>
                <a href="?key=<?php echo htmlspecialchars($key); ?>&action=symlink" class="btn">
                    Générer le Lien de Stockage (Symlink)
                </a>
                <a href="?key=<?php echo htmlspecialchars($key); ?>&action=clear-cache" class="btn">
                    Vider les Caches Laravel
                </a>
                <a href="?key=<?php echo htmlspecialchars($key); ?>&action=seed" class="btn">
                    Lancer les Seeders (ChurchSeeder)
                </a>
            </div>
        <?php endif; ?>

        <?php if ($action && $key === SECRET_KEY): ?>
            <div class="console">
                <h3>Sortie d'exécution (Artisan)</h3>
                <div class="console-box"><?php echo htmlspecialchars($output ?: "Commande exécutée sans retour texte."); ?></div>
            </div>
        <?php endif; ?>

        <div class="footer">
            Outil conçu pour le projet de l'Église MPF. Pour plus de détails, consultez <a href="HOSTINGER_DEPLOYMENT.md">HOSTINGER_DEPLOYMENT.md</a>.
        </div>
    </div>
</body>
</html>
