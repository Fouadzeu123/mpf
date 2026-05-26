# Configuration du Webhook NotchPay et Intégration Bible API

## 1. Configuration du Webhook NotchPay

### 1.1 URL du Webhook

L'endpoint webhook sécurisé est :

```
POST https://votre-domaine.com/webhooks/notchpay
```

### 1.2 Sécurité HMAC-SHA256

Pour chaque requête webhook, NotchPay envoie un header `X-Notchpay-Signature` contenant une signature HMAC-SHA256 du corps brut de la requête.

**Format du calcul :**

```php
signature = HMAC-SHA256(
    payload_brut,
    NOTCHPAY_PRIVATE_KEY
)
```

### 1.3 Configuration dans NotchPay Dashboard

1. Se connecter à https://dashboard.notchpay.co
2. Aller à Settings → Webhooks → Add Webhook
3. URL du webhook : `https://votre-domaine.com/webhooks/notchpay`
4. Events à sélectionner :
   - `payment.complete` ou `transaction.complete`
5. Sauvegarder

### 1.4 Variables d'Environnement

Vérifier que le fichier `.env` contient :

```env
NOTCHPAY_PUBLIC_KEY=pk.xxxxx
NOTCHPAY_PRIVATE_KEY=sk.xxxxx
NOTCHPAY_SANDBOX=false
NOTCHPAY_CALLBACK_URL=https://votre-domaine.com/paiements/callback
```

## 2. Configuration API Bible (scripture.api.bible)

### 2.1 Obtenir une Clé API

1. Visiter https://www.api.bible
2. S'inscrire gratuitement
3. Créer une application
4. Copier la clé API

### 2.2 Choisir une Bible (Bible ID)

Les bibles disponibles incluent :

- **Français Darby** : `de4e12af7f28f599-02` (défaut)
- **Français LSG** : `686e3df04ad30d09-01`
- **Français Seg21** : `7ca21ef99bfe43f0-01`
- **Français Crampon** : `0c36a6e66c947897-01`

Pour consulter toutes les bibles : https://api.scripture.api.bible/docs#/

### 2.3 Configuration du .env

```env
BIBLE_API_KEY=your_api_key_here
BIBLE_ID=de4e12af7f28f599-02
BIBLE_API_URL=https://api.scripture.api.bible/v1
```

## 3. Livres Bibliques Supportés

Le service `BibleVerseService` supporte maintenant les 66 livres de la Bible avec plusieurs variantes orthographiques :

### Ancien Testament (39 livres)

- Genèse, Exode, Lévitique, Nombres, Deutéronome
- Josué, Juges, Ruth
- 1/2 Samuel, 1/2 Rois, 1/2 Chroniques
- Esdras, Néhémie, Esther
- Job, Psaumes, Proverbes
- Ecclésiaste, Cantique des Cantiques
- Ésaïe/Isaïe, Jérémie, Lamentations, Ézéchiel, Daniel
- Osée, Joël, Amos, Abdias, Jonas, Michée
- Nahum, Habacuc, Sophonie, Aggée, Zacharie, Malachie

### Nouveau Testament (27 livres)

- Matthieu, Marc, Luc, Jean
- Actes
- Romains
- 1/2 Corinthiens, Galates, Éphésiens, Philippiens, Colossiens
- 1/2 Thessaloniciens
- 1/2 Timothée, Tite, Philémon
- Hébreux, Jacques
- 1/2 Pierre
- 1/2/3 Jean, Jude
- Apocalypse

## 4. Utilisation

### 4.1 Flux Automatique (Sans Action Requise)

Les versets sont envoyés automatiquement et asynchrone dans ces cas :

#### ✅ Lors du scan (mode Communion)
```
Scanner QR code du membre (mode Communion)
  ↓
ScannerService::process()
  ↓
CommunionService::prepare()
  ↓
SendBibleVerseJob dispatched (asynchrone)
  ↓
Verset envoyé via WhatsApp (non-bloquant)
  ↓
Scanner peut immédiatement scanner le suivant
```

#### ✅ Lors de la confirmation de paiement
```
Utilisateur effectue paiement NotchPay
  ↓
NotchPay envoie webhook POST → /webhooks/notchpay
  ↓
WebhookController valide signature HMAC
  ↓
CommunionService::prepare()
  ↓
SendBibleVerseJob dispatched (asynchrone)
  ↓
Verset envoyé via WhatsApp
```

**Important** : Aucune action manuelle requise - tout est automatique !

### 4.2 Envoi Manuel (Optional - Pour Cas Exceptionnels)

Si vous devez envoyer des versets manuellement à tous les membres :

```bash
# Envoyer à tous les membres actifs
php artisan send:verses --all

# Envoyer à un membre spécifique
php artisan send:verses --member-id=1

# Renvoyer les versets échoués
php artisan send:verses --retry
```

### 4.3 Configuration de la Queue (Requis)

Le système utilise une queue asynchrone pour l'envoi des versets. **Le queue worker DOIT être actif pour que les versets soient envoyés.**

#### Setup Initial

```bash
# Créer la table queue (si elle n'existe pas)
php artisan queue:table
php artisan migrate

# Configurer le driver queue dans .env
QUEUE_CONNECTION=database  # ou redis pour meilleure performance
```

#### 📌 En Développement

```bash
# Terminal 1 : Démarrer le serveur Laravel
php artisan serve

# Terminal 2 : Démarrer le queue worker
php artisan queue:work

# Ou avec verbose pour voir les jobs
php artisan queue:work --verbose
```

#### 📌 En Production (Supervisor)

Installer Supervisor :
```bash
# Ubuntu/Debian
sudo apt-get install supervisor
```

Créer `/etc/supervisor/conf.d/biblestory-queue.conf` :

```ini
[program:biblestory-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/bibleStory/artisan queue:work --queue=default --tries=3 --timeout=300
autostart=true
autorestart=true
numprocs=4
redirect_stderr=true
stdout_logfile=/path/to/bibleStory/storage/logs/queue.log
```

Puis :
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start biblestory-queue:*

# Vérifier le statut
sudo supervisorctl status
```

#### 📌 Vérifier les Jobs en Queue

```bash
# Voir les jobs en attente
php artisan queue:failed

# Voir l'historique
php artisan queue:failed-table

# Relancer les jobs échoués
php artisan queue:retry all
php artisan queue:retry <job-id>
```

## 5. Flux de Paiement avec Webhook Automatique

```
1. Utilisateur clique "Faire ma préparation"
   ↓
2. NotchPayService::initiate() crée une transaction (status: pending)
   ↓
3. Utilisateur est redirigé vers NotchPay Checkout
   ↓
4. Utilisateur effectue le paiement (visa, mtn, etc)
   ↓
5. NotchPay envoie webhook POST à /webhooks/notchpay
   ↓
6. WebhookController::notchpay()
   - Valide la signature HMAC-SHA256
   - Récupère la transaction
   - Met à jour le statut (status: paid)
   ↓
7. CommunionService::prepare() est appelée automatiquement
   - Génère un verset aléatoire
   - Crée CommunionPreparation
   - Dispatch SendBibleVerseJob
   ↓
8. Queue Worker récupère le job
   ↓
9. Verset est envoyé via WhatsApp (asynchrone, non-bloquant)
   ↓
10. CommunionPreparation::whatsapp_sent = true
```

**Important** : L'utilisateur ne reçoit pas le verset immédiatement après le paiement, mais quelques secondes après une fois que le queue worker traite le job. En développement avec le driver WhatsApp `log`, tout est instantané pour les tests.


## 6. Flux d'Envoi de Versets

### ✅ Flux Automatique (Scan ou Paiement)

```
Scan QR ou Paiement Confirmé
  ↓
CommunionService::prepare()
  ↓
SendBibleVerseJob::dispatch($member, $reference)
  ↓
Job ajouté à la Queue
  ↓
Queue Worker récupère le job
  ↓
BibleVerseService::fetchVerseByReference()
  ↓
Verset récupéré via API Bible (avec cache)
  ↓
WhatsAppService::sendVerse()
  ↓
Message envoyé via WhatsApp (Twilio ou Meta Business)
  ↓
CommunionPreparation::whatsapp_sent = true
  ↓
Historique enregistré (MemberVerseHistory)
```

### ⚙️ Composants Clés

| Composant | Rôle |
|-----------|------|
| **SendBibleVerseJob** | Job queué qui envoie le verset |
| **BibleVerseService** | Récupère le verset (cache 1 semaine) |
| **WhatsAppService** | Envoie via Twilio ou Meta Business API |
| **Queue Worker** | Exécute les jobs en arrière-plan |
| **CommunionPreparation** | Enregistre le statut d'envoi |

## 7. Tests du Webhook

### 7.1 Test Local (avec ngrok)

```bash
# Exposer l'application localement
ngrok http 8000

# Utiliser l'URL ngrok pour configurer le webhook
https://xxxx-xx-xxx-xxx-xx.ngrok.io/webhooks/notchpay
```

### 7.2 Test avec cURL

```bash
# Tester la route GET de callback
curl -X GET "http://localhost:8000/paiements/callback?reference=MPF-XXXXX"

# Tester le webhook POST avec signature
PRIVATE_KEY="sk_xxxxx"
PAYLOAD='{"reference":"MPF-XXXXX","status":"completed"}'
SIGNATURE=$(echo -n "$PAYLOAD" | openssl dgst -sha256 -mac HMAC -macopt "key:$PRIVATE_KEY" -hex | cut -d' ' -f2)

curl -X POST "http://localhost:8000/webhooks/notchpay" \
  -H "Content-Type: application/json" \
  -H "X-Notchpay-Signature: $SIGNATURE" \
  -d "$PAYLOAD"
```

## 8. Vérification de l'Intégration

### 8.1 Vérifier la Configuration

```bash
# Vérifier que les clés API sont configurées
php artisan tinker
config('services.bible.api_key')   # Doit retourner votre clé API
config('services.notchpay.private_key')  # Doit retourner votre clé privée
```

### 8.2 Tester un Verset

```bash
php artisan tinker
app(App\Services\BibleVerseService::class)->randomVerse()
```

### 8.3 Vérifier la Queue

```bash
# Lister les jobs en attente
php artisan queue:failed

# Relancer les jobs échoués
php artisan queue:retry all
```

## 9. Architecture Asynchrone

### ✅ Aucun Cron/Scheduler Requis

Les versets sont envoyés **automatiquement et instantanément** (après mise en queue) lors de :

- **Scan QR (mode Communion)** - Déclenche immédiatement
- **Confirmation de paiement** - Webhook NotchPay déclenche immédiatement
- **Vérification manuelle** - Route `/paiements/verifier/{reference}` déclenche immédiatement

**Pas besoin de** :
- ❌ Cron job quotidien (`send:verses --all`)
- ❌ Laravel Scheduler
- ❌ Task planning

### ✅ La Queue Worker Fait Tout

```
Déclencheur (scan/paiement)
  ↓
SendBibleVerseJob dispatched
  ↓
Job ajouté à la base queue_jobs
  ↓
Queue Worker récupère le job
  ↓
Envoie le verset
  ↓
Marque le job comme complété
```

**Important** : Sans Queue Worker actif, les jobs resteront en attente indéfiniment.

## 10. Dépannage

### ⚠️ Les versets ne s'envoient pas (Queue Worker non actif)

**Symptômes** :
- Scan effectué mais pas de message WhatsApp
- Paiement confirmé mais pas de verset
- Jobs visibles avec `php artisan queue:failed`

**Cause** : Aucun Queue Worker n'est actif pour traiter les jobs

**Solution** :
```bash
# Terminal 2 : Démarrer le queue worker
php artisan queue:work --verbose

# Voir les jobs en attente
php artisan queue:failed

# Relancer les jobs échoués une fois le worker démarré
php artisan queue:retry all
```

### Le verset n'a pas de texte

**Cause** : La clé API n'est pas configurée ou n'est pas valide.

**Solution** :
```bash
# Vérifier la clé API
php artisan tinker
config('services.bible.api_key')

# Tester l'appel API
Http::withToken('votre_cle')->get('https://api.scripture.api.bible/v1/bibles/de4e12af7f28f599-02/verses/GEN.1.1')
```

### Le webhook n'est pas reçu

**Cause** : URL webhook invalide ou pare-feu

**Solution** :
1. Vérifier que l'URL est accessible publiquement
2. Vérifier les logs Laravel (`storage/logs/`)
3. Utiliser `php artisan tail` pour voir les logs en temps réel

### Erreur de signature HMAC

**Cause** : Clé privée incorrecte

**Solution** :
1. Vérifier que `NOTCHPAY_PRIVATE_KEY` est correcte dans `.env`
2. Vérifier que la clé privée dans NotchPay Dashboard correspond

### Les jobs restent en queue indéfiniment

**Cause** : Queue Worker n'a jamais été démarré depuis le déploiement

**Solution** :
```bash
# Redémarrer le worker
sudo supervisorctl restart biblestory-queue:*

# Vérifier le statut
sudo supervisorctl status

# Vérifier les logs
tail -f /path/to/storage/logs/queue.log
```

## 11. Ressources

- [NotchPay Documentation](https://docs.notchpay.co)
- [API.Bible Documentation](https://www.api.bible/docs)
- [Laravel Queue Documentation](https://laravel.com/docs/queues)
- [HMAC-SHA256 Verification](https://en.wikipedia.org/wiki/HMAC)
