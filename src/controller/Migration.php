<?php
require_once "../entity/DatabaseConnection.php";

class Migration extends DatabaseConnection {

    public function __construct($password) {
        parent::__construct($password);
    }

    public function run() {
        try {
            // Création de la table User sans contrainte UNIQUE
            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS user (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    email TEXT NOT NULL,
                    phone TEXT NOT NULL,
                    password TEXT NOT NULL,
                    role TEXT NOT NULL,
                    isLocked BOOLEAN DEFAULT 0 -- 🔹 Ajout de la colonne isLocked
                );
            ");

            // Mise à jour de la table Client sans contrainte UNIQUE
            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS client (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name TEXT NOT NULL,
                    email TEXT NOT NULL,
                    phone TEXT NOT NULL,
                    public_key TEXT NOT NULL
                );
            ");

            // Création de la table Subscription sans contrainte UNIQUE
            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS subscription (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_id INTEGER NOT NULL,
                    client_id INTEGER NOT NULL,
                    quota INTEGER NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
                    FOREIGN KEY (client_id) REFERENCES client(id) ON DELETE CASCADE
                );
            ");

            // Création de la table Software sans contrainte UNIQUE
            $this->pdo->exec("
                CREATE TABLE software (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    version VARCHAR(255) NOT NULL,
                    target_platform VARCHAR(50) NOT NULL,
                    icon VARCHAR(255) NOT NULL,
                    compressed_file VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                );
            ");

            echo "Migration exécutée avec succès !";
        } catch (PDOException $e) {
            die("Erreur lors de la migration : " . $e->getMessage());
        }
    }
}

// Exécuter la migration
$password = "123";  // 🔹 Remplace ceci par ton mot de passe réel
$migration = new Migration($password);
$migration->run();
?>
