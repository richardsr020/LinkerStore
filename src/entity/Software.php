<?php

class Software extends DatabaseConnection {

    public function __construct() {
        parent::__construct();
    }

    // 🔹 Ajouter un logiciel
    public function create($name, $version) {
        $stmt = $this->pdo->prepare("INSERT INTO software (name, version) VALUES (:name, :version)");
        return $stmt->execute([
            ':name' => $name,
            ':version' => $version
        ]);
    }

    // 🔹 Récupérer un logiciel par ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM software WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // 🔹 Récupérer tous les logiciels
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM software");
        return $stmt->fetchAll();
    }

    // 🔹 Mettre à jour un logiciel
    public function update($id, $name, $version) {
        $stmt = $this->pdo->prepare("UPDATE software SET name = :name, version = :version WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':version' => $version
        ]);
    }

    // 🔹 Supprimer un logiciel
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM software WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>
