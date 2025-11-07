<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h2>🔍 Diagnostic Base de Données</h2>";

// 1. Vérifier SQLite
echo "<h3>1. Extension SQLite</h3>";
echo "SQLite3 chargé: " . (extension_loaded('sqlite3') ? '✅ OUI' : '❌ NON') . "<br>";
echo "PDO SQLite disponible: " . (in_array('sqlite', PDO::getAvailableDrivers()) ? '✅ OUI' : '❌ NON') . "<br>";

// 2. Test de connexion
echo "<h3>2. Test de connexion</h3>";
try {
    $test_db = new PDO('sqlite::memory:');
    echo "Connexion SQLite mémoire: ✅ RÉUSSI<br>";
} catch (Exception $e) {
    echo "Connexion SQLite mémoire: ❌ ÉCHEC - " . $e->getMessage() . "<br>";
}

// 3. Test avec votre classe Database
echo "<h3>3. Test avec votre classe Database</h3>";
try {
    require_once 'config/database.php';
    $db = new Database();
    $pdo = $db->getConnection();
    echo "Connexion via Database class: ✅ RÉUSSI<br>";
    
    // Vérifier les tables
    $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll();
    echo "Tables trouvées: " . count($tables) . "<br>";
    foreach($tables as $table) {
        echo "- " . $table['name'] . "<br>";
    }
    
} catch (Exception $e) {
    echo "Connexion via Database class: ❌ ÉCHEC - " . $e->getMessage() . "<br>";
}

// 4. Vérifier les permissions
echo "<h3>4. Permissions du dossier</h3>";
$data_dir = __DIR__ . '/data';
echo "Dossier data existe: " . (is_dir($data_dir) ? '✅ OUI' : '❌ NON') . "<br>";
echo "Dossier data accessible en écriture: " . (is_writable($data_dir) ? '✅ OUI' : '❌ NON') . "<br>";

if (is_dir($data_dir)) {
    // Lister les fichiers
    $files = scandir($data_dir);
    echo "Fichiers dans data/:<br>";
    foreach($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "- " . $file . " (" . filesize($data_dir . '/' . $file) . " octets)<br>";
        }
    }
}

// 5. Test d'écriture
echo "<h3>5. Test d'écriture</h3>";
$test_file = $data_dir . '/test_write.txt';
if (file_put_contents($test_file, 'test')) {
    echo "Écriture fichier test: ✅ RÉUSSI<br>";
    unlink($test_file); // Nettoyer
} else {
    echo "Écriture fichier test: ❌ ÉCHEC<br>";
}
?>