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
    // Vérifier la session admin
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        throw new Exception('Non autorisé');
    }

    $db = new Database();
    $pdo = $db->getConnection();

    $stmt = $pdo->query("
        SELECT id, phone, email, created_at 
        FROM leads 
        ORDER BY created_at DESC
        LIMIT 100
    ");
    
    $leads = $stmt->fetchAll();

    // Nettoyer la mémoire tampon et envoyer la réponse
    ob_clean();
    
    echo json_encode([
        'success' => true,
        'data' => $leads
    ]);

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