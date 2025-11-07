<?php
class Database {
    private $pdo;
    private $db_file;

    public function __construct() {
        try {
            // Chemin absolu sécurisé
            $this->db_file = realpath(dirname(__FILE__) . '/../data/skill.db');
            
            // Créer le dossier data s'il n'existe pas
            $data_dir = dirname($this->db_file);
            if (!is_dir($data_dir)) {
                mkdir($data_dir, 0755, true);
            }

            // Vérifier les permissions
            if (!is_writable($data_dir)) {
                throw new Exception("Le dossier data n'est pas accessible en écriture");
            }

            $this->pdo = new PDO("sqlite:" . $this->db_file);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            $this->createTables();
            
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Erreur de connexion à la base de données");
        } catch (Exception $e) {
            error_log("General error: " . $e->getMessage());
            throw new Exception($e->getMessage());
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

        // Table admin
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS admin (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT UNIQUE NOT NULL,
                password_hash TEXT NOT NULL
            )
        ");

        // Insérer l'admin par défaut
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