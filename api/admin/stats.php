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

    // Nettoyer la mémoire tampon et envoyer la réponse
    ob_clean();
    
    echo json_encode([
        'success' => true,
        'data' => [
            'total_leads' => (int)$total_leads,
            'today_leads' => (int)$today_leads,
            'leads_with_email' => (int)$leads_with_email,
            'leads_without_email' => (int)$leads_without_email
        ]
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