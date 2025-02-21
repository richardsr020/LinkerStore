<?php
class DatabaseConnection {
    protected $pdo;

    public function __construct() {
        try {
            // Connexion à SQLite avec chiffrement
            $this->pdo = new PDO("sqlite:../controller/linkerstore.db");
            
            // Définition du mot de passe pour le chiffrement de la base de données
            $this->pdo->exec("PRAGMA key = '123';");

            // Vérification de l'accès à la base
            $result = $this->pdo->query("SELECT count(*) FROM sqlite_master;");
            if (!$result) {
                throw new Exception("Échec de l'authentification à la base de données.");
            }

            // Configuration PDO
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    // Méthode pour récupérer l'instance PDO
    public function getPdo() {
        return $this->pdo;
    }
}
?>
