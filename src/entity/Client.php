<?php
require_once "DatabaseConnection.php";

class Client extends DatabaseConnection {

    public function __construct() {
        parent::__construct();
    }

    // 🔹 Ajouter un client
    public function create($name, $email, $phone, $publicKey) {
        $stmt = $this->pdo->prepare("INSERT INTO client (name, email, phone, public_key) VALUES (:name, :email, :phone, :publicKey)");
        return $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':publicKey' => $publicKey
        ]);
    }

    // 🔹 Récupérer un client par ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM client WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // 🔹 Récupérer tous les clients
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM client");
        return $stmt->fetchAll();
    }

    // 🔹 Mettre à jour un client
    public function update($id, $name, $email, $phone, $publicKey) {
        $stmt = $this->pdo->prepare("UPDATE client SET name = :name, email = :email, phone = :phone, public_key = :publicKey WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':publicKey' => $publicKey
        ]);
    }

    // 🔹 Supprimer un client
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM client WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // 🔹 Vérifier si un email existe déjà
    public function emailExists($email) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM client WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetchColumn() > 0;
    }

    // 🔹 Vérifier si un téléphone existe déjà
    public function phoneExists($phone) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM client WHERE phone = :phone");
        $stmt->execute([':phone' => $phone]);
        return $stmt->fetchColumn() > 0;
    }
}
?>
