<?php
// DÉSACTIVER l'affichage des erreurs pour éviter la pollution HTML
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Gérer les requêtes OPTIONS pour CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Logger pour debug (fichier texte)
$debug_log = __DIR__ . '/../../debug.log';

function log_debug($message) {
    global $debug_log;
    file_put_contents($debug_log, "[" . date('Y-m-d H:i:s') . "] " . $message . "\n", FILE_APPEND);
}

// DÉMARRER LA MÉMOIRE TAMPON pour capturer toute sortie non désirée
ob_start();

try {
    log_debug("=== DÉBUT REQUÊTE ===");

    // Vérifier la méthode
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Méthode non autorisée');
    }

    // Récupérer les données JSON
    $json_input = file_get_contents('php://input');
    log_debug("Données brutes reçues: " . $json_input);
    
    $input = json_decode($json_input, true);
    
    if (!$input || json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Données JSON invalides');
    }

    // Valider les champs requis
    $email = trim($input['email'] ?? '');
    $phone = trim($input['phone'] ?? '');

    if (empty($email)) {
        throw new Exception('L\'email est obligatoire');
    }

    if (empty($phone)) {
        throw new Exception('Le téléphone est obligatoire');
    }

    // Validation email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Format d\'email invalide');
    }

    // Validation téléphone (format français simplifié)
    $phone_clean = preg_replace('/[\s\-\.]/', '', $phone);
    if (!preg_match('/^(?:(?:\+|00)33|0)[1-9](\d{2}){4}$/', $phone_clean)) {
        throw new Exception('Format de téléphone invalide. Ex: 0612345678');
    }

    // Inclure la base de données
    $db_path = __DIR__ . '/../../config/database.php';
    if (!file_exists($db_path)) {
        throw new Exception('Fichier de configuration introuvable');
    }
    
    require_once $db_path;
    
    $db = new Database();
    $pdo = $db->getConnection();

    // Vérifier les doublons
    $stmt = $pdo->prepare("SELECT id FROM leads WHERE email = ? OR phone = ?");
    $stmt->execute([$email, $phone_clean]);
    $existing = $stmt->fetch();

    if ($existing) {
        throw new Exception('Ces coordonnées sont déjà enregistrées');
    }

    // Insérer le lead
    $stmt = $pdo->prepare("INSERT INTO leads (email, phone) VALUES (?, ?)");
    $success = $stmt->execute([$email, $phone_clean]);
    
    if (!$success) {
        throw new Exception('Erreur lors de l\'insertion en base');
    }

    $lead_id = $pdo->lastInsertId();
    log_debug("SUCCÈS - Lead inséré ID: " . $lead_id);

    // VIDER LA MÉMOIRE TAMPON pour éviter toute sortie non désirée
    ob_clean();
    
    // Réponse de succès
    echo json_encode([
        'success' => true,
        'message' => '✅ Merci ! Nous vous contacterons dès le lancement.',
        'lead_id' => $lead_id
    ]);

} catch (Exception $e) {
    // VIDER LA MÉMOIRE TAMPON et envoyer l'erreur JSON
    ob_clean();
    
    log_debug("ERREUR: " . $e->getMessage());
    
    http_response_code(200); // Toujours 200 pour que le JavaScript puisse lire la réponse
    echo json_encode([
        'success' => false,
        'message' => '❌ ' . $e->getMessage()
    ]);
}

// S'ASSURER qu'aucune sortie n'est envoyée après
ob_end_flush();
?>