<?php
// DÉSACTIVER l'affichage des erreurs
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

// Démarrer la mémoire tampon
ob_start();

session_start();
header('Content-Type: application/json');

require_once '../../config/database.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Méthode non autorisée');
    }

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        throw new Exception('Identifiants requis');
    }

    $db = new Database();
    $pdo = $db->getConnection();

    $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password_hash'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['login_time'] = time();
        
        // Nettoyer et envoyer la réponse
        ob_clean();
        echo json_encode([
            'success' => true, 
            'message' => 'Connexion réussie'
        ]);
    } else {
        throw new Exception('Identifiants incorrects');
    }

} catch (Exception $e) {
    // Nettoyer et envoyer l'erreur
    ob_clean();
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage()
    ]);
}

ob_end_flush();
?>