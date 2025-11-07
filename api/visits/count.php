<?php
// DÉSACTIVER l'affichage des erreurs
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

// Démarrer la mémoire tampon
ob_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Gérer les requêtes OPTIONS pour CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    // Vérifier la méthode
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Méthode non autorisée');
    }

    // Récupérer les données
    $json_input = file_get_contents('php://input');
    $input = json_decode($json_input, true);
    
    if (!$input || json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Données JSON invalides');
    }

    // Chemin du fichier de comptage
    $visits_file = __DIR__ . '/../../data/visits.txt';
    $data_dir = dirname($visits_file);
    
    // Créer le dossier data s'il n'existe pas
    if (!is_dir($data_dir)) {
        mkdir($data_dir, 0755, true);
    }

    // Lire ou initialiser le compteur
    $visits_data = [
        'total_visits' => 0,
        'today_visits' => 0,
        'last_reset' => date('Y-m-d'),
        'daily_visits' => []
    ];

    if (file_exists($visits_file)) {
        $existing_data = file_get_contents($visits_file);
        $visits_data = json_decode($existing_data, true) ?: $visits_data;
    }

    // Vérifier si on change de jour
    $today = date('Y-m-d');
    if ($visits_data['last_reset'] !== $today) {
        $visits_data['today_visits'] = 0;
        $visits_data['last_reset'] = $today;
    }

    // Incrémenter les compteurs
    $visits_data['total_visits']++;
    $visits_data['today_visits']++;

    // Enregistrer la visite quotidienne
    if (!isset($visits_data['daily_visits'][$today])) {
        $visits_data['daily_visits'][$today] = 0;
    }
    $visits_data['daily_visits'][$today]++;

    // Limiter l'historique à 30 jours
    $visits_data['daily_visits'] = array_slice($visits_data['daily_visits'], -30, 30, true);

    // Sauvegarder les données
    file_put_contents($visits_file, json_encode($visits_data, JSON_PRETTY_PRINT));

    // Nettoyer et envoyer la réponse
    ob_clean();
    echo json_encode([
        'success' => true,
        'message' => 'Visite enregistrée',
        'total_visits' => $visits_data['total_visits'],
        'today_visits' => $visits_data['today_visits']
    ]);

} catch (Exception $e) {
    // Nettoyer et envoyer l'erreur
    ob_clean();
    echo json_encode([
        'success' => false,
        'message' => 'Erreur: ' . $e->getMessage()
    ]);
}

ob_end_flush();
?>