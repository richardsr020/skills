<?php
// DÉSACTIVER l'affichage des erreurs
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

// Démarrer la mémoire tampon
ob_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

session_start();

try {
    // Vérifier la session admin pour les stats
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        throw new Exception('Non autorisé');
    }

    // Chemin du fichier de comptage
    $visits_file = __DIR__ . '/../../data/visits.txt';
    
    // Lire les données de visites
    $visits_data = [
        'total_visits' => 0,
        'today_visits' => 0,
        'daily_visits' => []
    ];

    if (file_exists($visits_file)) {
        $existing_data = file_get_contents($visits_file);
        $visits_data = json_decode($existing_data, true) ?: $visits_data;
    }

    // Récupérer le nombre de leads depuis la base
    require_once __DIR__ . '/../../config/database.php';
    $db = new Database();
    $pdo = $db->getConnection();

    $stmt = $pdo->query("SELECT COUNT(*) as total_leads FROM leads");
    $total_leads = $stmt->fetchColumn();

    // Calculer le taux de conversion
    $conversion_rate = 0;
    if ($visits_data['total_visits'] > 0) {
        $conversion_rate = round(($total_leads / $visits_data['total_visits']) * 100, 2);
    }

    // Nettoyer et envoyer la réponse
    ob_clean();
    echo json_encode([
        'success' => true,
        'data' => [
            'total_visits' => (int)$visits_data['total_visits'],
            'today_visits' => (int)$visits_data['today_visits'],
            'total_leads' => (int)$total_leads,
            'conversion_rate' => $conversion_rate,
            'daily_visits' => $visits_data['daily_visits']
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