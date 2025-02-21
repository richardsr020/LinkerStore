<?php

class User extends DatabaseConnection {
    
    public function __construct() {
        parent::__construct();
    }

    /**
     * 🔹 Ajouter un nouvel utilisateur
     */
    public function createUser($email, $phone, $password, $role = 'USER', $isLocked = 0) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->pdo->prepare("INSERT INTO user (email, phone, password, role, isLocked) VALUES (:email, :phone, :password, :role, :isLocked)");
            $stmt->execute([
                ':email' => $email,
                ':phone' => $phone,
                ':password' => $hashedPassword,
                ':role' => $role,
                ':isLocked' => $isLocked
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
    public function updateUser($id, $email, $phone, $password = null, $role = null, $isLocked = null) {
        try {
            // Préparation de la requête
            $query = "UPDATE user SET email = :email, phone = :phone";
            $params = [
                ':email' => $email,
                ':phone' => $phone,
                ':id' => $id
            ];

            // Ajout du mot de passe si présent
            if ($password) {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $query .= ", password = :password";
                $params[':password'] = $hashedPassword;
            }

            // Ajout du rôle si présent
            if ($role) {
                $query .= ", role = :role";
                $params[':role'] = $role;
            }

            // Ajout de isLocked si présent
            if ($isLocked !== null) {
                $query .= ", isLocked = :isLocked";
                $params[':isLocked'] = $isLocked;
            }

            // Exécution de la requête
            $query .= " WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);

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

    /**
     * 🔹 Vérifier si un utilisateur est verrouillé
     */
    public function isUserLocked($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT isLocked FROM user WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $user = $stmt->fetch();
            return $user['isLocked'] == 1;
        } catch (PDOException $e) {
            die("Erreur lors de la vérification du verrouillage : " . $e->getMessage());
        }
    }

       /**
     * 🔹 Verrouiller ou déverrouiller un utilisateur
     */
    public function lock($id, $isLocked) {
        try {
            $stmt = $this->pdo->prepare("UPDATE user SET is_locked = :is_locked WHERE id = :id");
            $stmt->execute([
                ':is_locked' => $isLocked ? 1 : 0,
                ':id' => $id
            ]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            die("Erreur lors de la mise à jour du statut de verrouillage : " . $e->getMessage());
        }
    }

    /**
     * 🔹 Mettre à jour le rôle d'un utilisateur
     */
    public function setRole($id, $role) {
        try {
            $stmt = $this->pdo->prepare("UPDATE user SET role = :role WHERE id = :id");
            $stmt->execute([
                ':role' => $role,
                ':id' => $id
            ]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            die("Erreur lors de la mise à jour du rôle : " . $e->getMessage());
        }
    }
}

?>
