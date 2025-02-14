<?php
require_once "DatabaseConnection.php";

class Migration extends DatabaseConnection {

    public function __construct($password) {
        parent::__construct($password);
    }

    public function run() {
        try {
            // Création de la table User
            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS user (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    email TEXT UNIQUE NOT NULL,
                    phone TEXT UNIQUE NOT NULL,
                    password TEXT NOT NULL,
                    isLocked BOOLEAN DEFAULT 0 -- 🔹 Ajout de la colonne isLocked
                );
            ");

            // Création de la table Client
            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS client (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name TEXT UNIQUE NOT NULL,
                    email TEXT UNIQUE NOT NULL,
                    phone TEXT UNIQUE NOT NULL,
                    public_key TEXT NOT NULL
                );
            ");

            // Création de la table Subscription
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
