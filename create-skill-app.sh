#!/bin/bash

# Script de création de l'application SKILL
# Usage: ./create-skill-app.sh

set -e  # Arrêter le script en cas d'erreur

echo "🚀 Création de l'application SKILL..."
echo "=========================================="

# Création de l'arborescence
echo "📁 Création de l'arborescence..."
mkdir -p skill/{config,api/lead,api/admin,assets/{css,js},data}

# Navigation dans le dossier
cd skill

# 1. Fichier de configuration de la base de données
echo "🗃️  Création de config/database.php..."
cat > config/database.php << 'EOF'
<?php
class Database {
    private $pdo;
    private $db_file = '../data/skill.db';

    public function __construct() {
        try {
            // Créer le dossier data s'il n'existe pas
            if (!is_dir('../data')) {
                mkdir('../data', 0755, true);
            }

            $this->pdo = new PDO("sqlite:" . $this->db_file);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            $this->createTables();
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Erreur de connexion à la base de données");
        }
    }

    private function createTables() {
        // Table des leads
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS leads (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                phone TEXT UNIQUE NOT NULL,
                email TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");

        // Table admin avec un compte par défaut
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS admin (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT UNIQUE NOT NULL,
                password_hash TEXT NOT NULL
            )
        ");

        // Insérer l'admin par défaut si nécessaire
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM admin");
        if ($stmt->fetchColumn() == 0) {
            $password_hash = password_hash('admin123', PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare("INSERT INTO admin (username, password_hash) VALUES (?, ?)");
            $stmt->execute(['admin', $password_hash]);
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}
?>
EOF

# 2. Feuille de style CSS
echo "🎨 Création de assets/css/style.css..."
cat > assets/css/style.css << 'EOF'
/* Reset et base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.6;
    color: #333;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* Cartes */
.card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    padding: 30px;
    margin-bottom: 30px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

/* En-têtes */
.header {
    text-align: center;
    margin-bottom: 40px;
    color: white;
}

.header h1 {
    font-size: 2.5rem;
    margin-bottom: 10px;
    font-weight: 300;
}

.header p {
    font-size: 1.1rem;
    opacity: 0.9;
}

/* Formulaires */
}

    display: block;
    margin-bottom: 8px;
    color: #555;

.form-control {
    padding: 12px 16px;
    border: 2px solid #e1e5e9;
    border-radius: 8px;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.form-control:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
}

.form-control.error {
    border-color: #dc3545;
}

/* Boutons */
.btn {
    display: inline-block;
    padding: 12px 30px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    text-align: center;
}

.btn:hover {
    background: #0056b3;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
}

.btn-block {
    display: block;
    width: 100%;
}

.btn-logout {
    background: #dc3545;
}

.btn-logout:hover {
    background: #c82333;
}

/* Messages */
.alert {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    border-left: 4px solid;
}

.alert-success {
    background: #d4edda;
    border-color: #28a745;
    color: #155724;
}

.alert-error {
    background: #f8d7da;
    border-color: #dc3545;
    color: #721c24;
}

.alert-info {
    background: #d1ecf1;
    border-color: #17a2b8;
    color: #0c5460;
}

/* Dashboard */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 25px;
    border-radius: 12px;
    text-align: center;
}

    border-color: #007bff;
    font-size: 16px;
    width: 100%;
.stat-number {
    font-size: 2.5rem;
}
    font-weight: bold;
    font-weight: 500;
    margin-bottom: 10px;
.form-group label {
}

    margin-bottom: 20px;
.stat-label {
    font-size: 1rem;

    opacity: 0.9;
}


/* Tableau des leads */
.leads-table {
    width: 100%;

    border-collapse: collapse;
    margin-top: 20px;
}

.leads-table th,
.leads-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e1e5e9;
}

.leads-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #555;
}

.leads-table tr:hover {
    background: #f8f9fa;

}


/* Navigation admin */
.admin-nav {

    display: flex;

    justify-content: space-between;

    align-items: center;
    margin-bottom: 30px;

}

.admin-header {
    color: white;

}

/* Responsive */
@media (max-width: 768px) {
    .container {

        padding: 15px;
    }
    
    .card {
        padding: 20px;

    }
    
    .header h1 {
        font-size: 2rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .leads-table {
        font-size: 14px;
    }
}


/* Loading */
.loading {

    text-align: center;

    padding: 20px;

    color: #666;

}


.hidden {
    display: none;
}
EOF


# 3. Page publique principale
echo "📄 Création de index.php..."

cat > index.php << 'EOF'
<?php

require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SKILL - Plateforme de Collecte de Leads</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 SKILL</h1>
            <p>Plateforme de collecte de leads - Laissez-nous vos coordonnées</p>
        </div>

        <div class="card">
            <div id="message-container"></div>

            

            <form id="leadForm">

                <div class="form-group">

                    <label for="phone">Téléphone *</label>

                    <input type="tel" id="phone" name="phone" class="form-control" 

                           placeholder="06 12 34 56 78" required>
                    <small class="error-message" id="phone-error"></small>
                </div>


                <div class="form-group">
                    <label for="email">Email (optionnel)</label>

                    <input type="email" id="email" name="email" class="form-control" 
                           placeholder="votre@email.com">
                    <small class="error-message" id="email-error"></small>
                </div>

                <button type="submit" class="btn btn-block" id="submitBtn">
                    Envoyer mes coordonnées
                </button>
            </form>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="admin.php" class="btn" style="background: #28a745;">
                🔐 Accès Administrateur
            </a>

        </div>

    </div>


    <script src="assets/js/form.js"></script>

</body>

</html>

EOF


# 4. JavaScript pour le formulaire

echo "📝 Création de assets/js/form.js..."
cat > assets/js/form.js << 'EOF'

class LeadForm {

    constructor() {
        this.form = document.getElementById('leadForm');
        this.phoneInput = document.getElementById('phone');

        this.emailInput = document.getElementById('email');
        this.submitBtn = document.getElementById('submitBtn');
        this.messageContainer = document.getElementById('message-container');

        
        this.initEvents();
    }

    initEvents() {
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        this.phoneInput.addEventListener('input', () => this.validatePhone());

        this.emailInput.addEventListener('input', () => this.validateEmail());
    }

    validatePhone() {
        const phone = this.phoneInput.value.trim();
        const phoneRegex = /^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/;
        

        if (!phone) {

            this.showError(this.phoneInput, 'Le téléphone est requis');

            return false;

        }


        if (!phoneRegex.test(phone.replace(/\s/g, ''))) {
            this.showError(this.phoneInput, 'Format invalide (ex: 06 12 34 56 78)');

            return false;
        }


        this.clearError(this.phoneInput);
        return true;
    }

    validateEmail() {
        const email = this.emailInput.value.trim();
        
        if (email && !this.isValidEmail(email)) {
            this.showError(this.emailInput, 'Format d\'email invalide');
            return false;
        }

        this.clearError(this.emailInput);
        return true;

    }


    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        return emailRegex.test(email);

    }

    showError(input, message) {
        input.classList.add('error');

        const errorElement = document.getElementById(input.id + '-error');
        if (errorElement) {

            errorElement.textContent = message;
            errorElement.style.color = '#dc3545';
        }

    }

    clearError(input) {
        input.classList.remove('error');

        const errorElement = document.getElementById(input.id + '-error');
        if (errorElement) {
            errorElement.textContent = '';
        }

    }

    showMessage(message, type = 'success') {
        this.messageContainer.innerHTML = `
            <div class="alert alert-${type}">
                ${message}
            </div>
        `;
        
        setTimeout(() => {
            this.messageContainer.innerHTML = '';

        }, 5000);

    }


    setLoading(loading) {

        this.submitBtn.disabled = loading;
        this.submitBtn.textContent = loading ? 'Envoi en cours...' : 'Envoyer mes coordonnées';

    }


    async handleSubmit(e) {

        e.preventDefault();


        const isPhoneValid = this.validatePhone();
        const isEmailValid = this.validateEmail();


        if (!isPhoneValid || !isEmailValid) {

            this.showMessage('Veuillez corriger les erreurs du formulaire', 'error');
            return;
        }

        const formData = {
            phone: this.phoneInput.value.trim(),
            email: this.emailInput.value.trim() || null
        };

        this.setLoading(true);


        try {

            const response = await fetch('api/lead/add.php', {

                method: 'POST',

                headers: {

                    'Content-Type': 'application/json',

                },
                body: JSON.stringify(formData)
            });


            const result = await response.json();

            if (result.success) {

                this.showMessage('✅ Vos coordonnées ont été enregistrées avec succès !');
                this.form.reset();

            } else {
                this.showMessage(result.message || '❌ Une erreur est survenue', 'error');
            }
        } catch (error) {
            console.error('Erreur:', error);
            this.showMessage('❌ Erreur de connexion au serveur', 'error');

        } finally {

            this.setLoading(false);
        }

    }
}


// Initialisation quand la page est chargée

document.addEventListener('DOMContentLoaded', () => {

    new LeadForm();

});

EOF


# 5. API d'ajout de leads

echo "🔧 Création de api/lead/add.php..."
cat > api/lead/add.php << 'EOF'

<?php
header('Content-Type: application/json');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

header('Access-Control-Allow-Headers: Content-Type');


require_once '../../config/database.php';

// Vérifier que c'est bien une requête POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);

    exit;
}

// Récupérer les données JSON
$input = json_decode(file_get_contents('php://input'), true);


if (!$input) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Données invalides']);
    exit;
}

$phone = $input['phone'] ?? '';
$email = $input['email'] ?? null;

// Validation des données
if (empty($phone)) {
    echo json_encode(['success' => false, 'message' => 'Le téléphone est obligatoire']);
    exit;
}

// Nettoyer et valider le format du téléphone
$phone = preg_replace('/\s+/', '', $phone);
if (!preg_match('/^(?:(?:\+|00)33|0)[1-9]\d{8}$/', $phone)) {
    echo json_encode(['success' => false, 'message' => 'Format de téléphone invalide']);
    exit;
}

// Validation email si fourni
if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Format d\'email invalide']);
    exit;
}

try {
    $db = new Database();
    $pdo = $db->getConnection();

    // Vérifier si le téléphone existe déjà
    $stmt = $pdo->prepare("SELECT id FROM leads WHERE phone = ?");
    $stmt->execute([$phone]);
    
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Ce numéro de téléphone est déjà enregistré']);
        exit;
    }

    // Insérer le nouveau lead
    $stmt = $pdo->prepare("INSERT INTO leads (phone, email) VALUES (?, ?)");
    $stmt->execute([$phone, $email]);

    echo json_encode([
        'success' => true, 
        'message' => 'Lead enregistré avec succès',
        'lead_id' => $pdo->lastInsertId()
    ]);

} catch (PDOException $e) {
    error_log("Database error in add.php: " . $e->getMessage());
    
    if ($e->getCode() == 23000) { // Violation d'unicité
        echo json_encode(['success' => false, 'message' => 'Ce numéro de téléphone est déjà enregistré']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement']);
    }
} catch (Exception $e) {
    error_log("Error in add.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erreur serveur']);
}
?>
EOF

# 6. Page d'administration
echo "🔐 Création de admin.php..."
cat > admin.php << 'EOF'
<?php
session_start();
require_once 'config/database.php';

// Vérifier si l'admin est connecté
$isLoggedIn = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

// Si pas connecté, rediriger vers la page de login
if (!$isLoggedIn && basename($_SERVER['PHP_SELF']) !== 'admin.php') {
    header('Location: admin.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SKILL - Administration</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <?php if (!$isLoggedIn): ?>
            <!-- Formulaire de connexion -->
            <div class="header">
                <h1>🔐 Connexion Admin</h1>
                <p>Accès réservé aux administrateurs</p>
            </div>

            <div class="card" style="max-width: 400px; margin: 0 auto;">
                <div id="message-container"></div>
                
                <form id="loginForm">
                    <div class="form-group">
                        <label for="username">Nom d'utilisateur</label>
                        <input type="text" id="username" name="username" class="form-control" 
                               value="admin" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" class="form-control" 
                               value="admin123" required>
                    </div>

                    <button type="submit" class="btn btn-block" id="loginBtn">
                        Se connecter
                    </button>
                </form>
            </div>

        <?php else: ?>
            <!-- Dashboard admin -->
            <div class="admin-nav">
                <div class="admin-header">
                    <h1>📊 Dashboard SKILL</h1>
                    <p>Gestion des leads collectés</p>
                </div>
                <button onclick="logout()" class="btn btn-logout">Déconnexion</button>
            </div>

            <!-- Statistiques -->
            <div class="stats-grid" id="stats-container">
                <div class="loading">Chargement des statistiques...</div>
            </div>

            <!-- Liste des leads -->
            <div class="card">
                <h2 style="margin-bottom: 20px;">📋 Liste des Leads</h2>
                <div id="leads-container">
                    <div class="loading">Chargement des leads...</div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="assets/js/admin.js"></script>
</body>
</html>
EOF

# 7. JavaScript pour l'administration
echo "📊 Création de assets/js/admin.js..."
cat > assets/js/admin.js << 'EOF'
class AdminDashboard {
    constructor() {
        this.isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
        
        if (this.isLoggedIn) {
            this.initDashboard();
        } else {
            this.initLogin();
        }
    }

    initLogin() {
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', (e) => this.handleLogin(e));
        }
    }

    initDashboard() {
        this.loadStats();
        this.loadLeads();
        
        // Actualiser toutes les 30 secondes
        setInterval(() => {
            this.loadStats();
            this.loadLeads();
        }, 30000);
    }

    async handleLogin(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const loginBtn = document.getElementById('loginBtn');
        const messageContainer = document.getElementById('message-container');

        loginBtn.disabled = true;
        loginBtn.textContent = 'Connexion...';

        try {
            const response = await fetch('api/admin/login.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                messageContainer.innerHTML = `
                    <div class="alert alert-success">
                        ✅ Connexion réussie! Redirection...
                    </div>
                `;
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                messageContainer.innerHTML = `
                    <div class="alert alert-error">
                        ❌ ${result.message || 'Erreur de connexion'}
                    </div>
                `;
            }
        } catch (error) {
            console.error('Erreur:', error);
            messageContainer.innerHTML = `
                <div class="alert alert-error">
                    ❌ Erreur de connexion au serveur
                </div>
            `;
        } finally {
            loginBtn.disabled = false;
            loginBtn.textContent = 'Se connecter';
        }
    }

    async loadStats() {
        try {
            const response = await fetch('api/admin/stats.php');
            const stats = await response.json();

            if (stats.success) {
                this.renderStats(stats.data);
            } else {
                this.showError('Erreur lors du chargement des statistiques');
            }
        } catch (error) {
            console.error('Erreur:', error);
            this.showError('Erreur de connexion');
        }
    }

    async loadLeads() {
        try {
            const response = await fetch('api/admin/leads.php');
            const leads = await response.json();

            if (leads.success) {
                this.renderLeads(leads.data);
            } else {
                this.showError('Erreur lors du chargement des leads');
            }
        } catch (error) {
            console.error('Erreur:', error);
            this.showError('Erreur de connexion');
        }
    }

    renderStats(stats) {
        const statsContainer = document.getElementById('stats-container');
        
        statsContainer.innerHTML = `
            <div class="stat-card">
                <div class="stat-number">${stats.total_leads}</div>
                <div class="stat-label">Leads Totaux</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">${stats.today_leads}</div>
                <div class="stat-label">Leads Aujourd'hui</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">${stats.leads_with_email}</div>
                <div class="stat-label">Avec Email</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">${stats.leads_without_email}</div>
                <div class="stat-label">Sans Email</div>
            </div>
        `;
    }

    renderLeads(leads) {
        const leadsContainer = document.getElementById('leads-container');
        
        if (leads.length === 0) {
            leadsContainer.innerHTML = '<div class="alert alert-info">Aucun lead pour le moment</div>';
            return;
        }

        let html = `
            <table class="leads-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
        `;

        leads.forEach(lead => {
            html += `
                <tr>
                    <td>${lead.id}</td>
                    <td>${this.formatPhone(lead.phone)}</td>
                    <td>${lead.email || '<em style="color: #999;">Non renseigné</em>'}</td>
                    <td>${this.formatDate(lead.created_at)}</td>
                </tr>
            `;
        });

        html += '</tbody></table>';
        leadsContainer.innerHTML = html;
    }

    formatPhone(phone) {
        // Formater le téléphone français
        return phone.replace(/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/, '$1 $2 $3 $4 $5');
    }

    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    showError(message) {
        const statsContainer = document.getElementById('stats-container');
        const leadsContainer = document.getElementById('leads-container');
        
        if (statsContainer) {
            statsContainer.innerHTML = `<div class="alert alert-error">${message}</div>`;
        }
        if (leadsContainer) {
            leadsContainer.innerHTML = `<div class="alert alert-error">${message}</div>`;
        }
    }
}

// Fonction de déconnexion
function logout() {
    if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
        fetch('api/admin/logout.php')
            .then(() => {
                window.location.reload();
            })
            .catch(error => {
                console.error('Erreur:', error);
                window.location.reload();
            });
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    new AdminDashboard();
});
EOF

# 8. API d'authentification admin
echo "🔑 Création de api/admin/login.php..."
cat > api/admin/login.php << 'EOF'
<?php
session_start();
header('Content-Type: application/json');

require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Identifiants requis']);
    exit;
}

try {
    $db = new Database();
    $pdo = $db->getConnection();

    $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password_hash'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['login_time'] = time();
        
        echo json_encode(['success' => true, 'message' => 'Connexion réussie']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Identifiants incorrects']);
    }

} catch (Exception $e) {
    error_log("Login error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erreur serveur']);
}
?>
EOF

# 9. API des statistiques
echo "📈 Création de api/admin/stats.php..."
cat > api/admin/stats.php << 'EOF'
<?php
session_start();
header('Content-Type: application/json');

require_once '../../config/database.php';

// Vérifier la session admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non autorisé']);
    exit;
}

try {
    $db = new Database();
    $pdo = $db->getConnection();

    // Total des leads
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM leads");
    $total_leads = $stmt->fetchColumn();

    // Leads du jour
    $stmt = $pdo->query("SELECT COUNT(*) as today FROM leads WHERE DATE(created_at) = DATE('now')");
    $today_leads = $stmt->fetchColumn();

    // Leads avec email
    $stmt = $pdo->query("SELECT COUNT(*) as with_email FROM leads WHERE email IS NOT NULL AND email != ''");
    $leads_with_email = $stmt->fetchColumn();

    // Leads sans email
    $leads_without_email = $total_leads - $leads_with_email;

    echo json_encode([
        'success' => true,
        'data' => [
            'total_leads' => $total_leads,
            'today_leads' => $today_leads,
            'leads_with_email' => $leads_with_email,
            'leads_without_email' => $leads_without_email
        ]
    ]);

} catch (Exception $e) {
    error_log("Stats error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erreur lors du chargement des statistiques']);
}
?>
EOF

# 10. API de liste des leads
echo "📋 Création de api/admin/leads.php..."
cat > api/admin/leads.php << 'EOF'
<?php
session_start();
header('Content-Type: application/json');

require_once '../../config/database.php';

// Vérifier la session admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non autorisé']);
    exit;
}

try {
    $db = new Database();
    $pdo = $db->getConnection();

    $stmt = $pdo->query("
        SELECT id, phone, email, created_at 
        FROM leads 
        ORDER BY created_at DESC
        LIMIT 100
    ");
    
    $leads = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'data' => $leads
    ]);

} catch (Exception $e) {
    error_log("Leads error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erreur lors du chargement des leads']);
}
?>
EOF

# 11. API de déconnexion
echo "🚪 Création de api/admin/logout.php..."
cat > api/admin/logout.php << 'EOF'
<?php
session_start();

// Détruire la session
session_unset();
session_destroy();

// Rediriger vers la page admin
header('Location: ../../admin.php');
exit;
?>
EOF

# 12. Fichier README
echo "📖 Création de README.md..."
cat > README.md << 'EOF'
# 🚀 SKILL - Plateforme de Collecte de Leads

Application web minimaliste pour collecter et gérer des leads (téléphone + email).

## 🏗️ Architecture

- **Frontend** : JavaScript vanilla + CSS moderne
- **Backend** : PHP API REST
- **Base de données** : SQLite
- **Authentification** : Sessions PHP sécurisées

## 📁 Structure

