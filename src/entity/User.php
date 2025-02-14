<?php


class User extends DatabaseConnection {
    
    public function __construct() {
        parent::__construct();
    }

    /**
     * 🔹 Ajouter un nouvel utilisateur
     */
    public function createUser($email, $phone, $password) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->pdo->prepare("INSERT INTO user (email, phone, password) VALUES (:email, :phone, :password)");
            $stmt->execute([
                ':email' => $email,
                ':phone' => $phone,
                ':password' => $hashedPassword
            ]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            die("Erreur lors de l'ajout de l'utilisateur : " . $e->getMessage());
        }
    }

    /**
     * 🔹 Récupérer un utilisateur par son ID
     */
    public function getUserById($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM user WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            die("Erreur lors de la récupération de l'utilisateur : " . $e->getMessage());
        }
    }

    /**
     * 🔹 Récupérer tous les utilisateurs
     */
    public function getAllUsers() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM user");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die("Erreur lors de la récupération des utilisateurs : " . $e->getMessage());
        }
    }

    /**
     * 🔹 Mettre à jour les informations d'un utilisateur
     */
    public function updateUser($id, $email, $phone, $password = null) {
        try {
            if ($password) {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $this->pdo->prepare("UPDATE user SET email = :email, phone = :phone, password = :password WHERE id = :id");
                $stmt->execute([
                    ':email' => $email,
                    ':phone' => $phone,
                    ':password' => $hashedPassword,
                    ':id' => $id
                ]);
            } else {
                $stmt = $this->pdo->prepare("UPDATE user SET email = :email, phone = :phone WHERE id = :id");
                $stmt->execute([
                    ':email' => $email,
                    ':phone' => $phone,
                    ':id' => $id
                ]);
            }
            return $stmt->rowCount();
        } catch (PDOException $e) {
            die("Erreur lors de la mise à jour de l'utilisateur : " . $e->getMessage());
        }
    }

    /**
     * 🔹 Supprimer un utilisateur
     */
    public function deleteUser($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM user WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            die("Erreur lors de la suppression de l'utilisateur : " . $e->getMessage());
        }
    }

    /**
     * 🔹 Vérifier si un email existe déjà
     */
    public function emailExists($email) {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM user WHERE email = :email");
            $stmt->execute([':email' => $email]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            die("Erreur lors de la vérification de l'email : " . $e->getMessage());
        }
    }

    /**
     * 🔹 Vérifier si un numéro de téléphone existe déjà
     */
    public function phoneExists($phone) {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM user WHERE phone = :phone");
            $stmt->execute([':phone' => $phone]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            die("Erreur lors de la vérification du téléphone : " . $e->getMessage());
        }
    }

    /**
     * 🔹 Authentifier un utilisateur avec son email et mot de passe
     */
    public function authenticate($email, $password) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM user WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                return $user;
            }
            return null;
        } catch (PDOException $e) {
            die("Erreur lors de l'authentification : " . $e->getMessage());
        }
    }
}



?>
