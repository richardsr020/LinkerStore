<?php
require_once "DatabaseConnection.php";

class Subscription extends DatabaseConnection {

    public function __construct() {
        parent::__construct();
    }

    // 🔹 Ajouter un abonnement
    public function create($userId, $clientId, $quota) {
        $stmt = $this->pdo->prepare("
            INSERT INTO subscription (user_id, client_id, quota) 
            VALUES (:userId, :clientId, :quota)
        ");
        return $stmt->execute([
            ':userId' => $userId,
            ':clientId' => $clientId,
            ':quota' => $quota
        ]);
    }

    // 🔹 Récupérer un abonnement par ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM subscription WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // 🔹 Récupérer tous les abonnements
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM subscription");
        return $stmt->fetchAll();
    }

    // 🔹 Récupérer les abonnements d'un utilisateur
    public function getByUserId($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM subscription WHERE user_id = :userId");
        $stmt->execute([':userId' => $userId]);
        return $stmt->fetchAll();
    }

    // 🔹 Récupérer les abonnements d'un client
    public function getByClientId($clientId) {
        $stmt = $this->pdo->prepare("SELECT * FROM subscription WHERE client_id = :clientId");
        $stmt->execute([':clientId' => $clientId]);
        return $stmt->fetchAll();
    }

    // 🔹 Mettre à jour un abonnement (modifier le quota)
    public function update($id, $quota) {
        $stmt = $this->pdo->prepare("
            UPDATE subscription 
            SET quota = :quota 
            WHERE id = :id
        ");
        return $stmt->execute([
            ':id' => $id,
            ':quota' => $quota
        ]);
    }

    // 🔹 Supprimer un abonnement par ID
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM subscription WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // 🔹 Vérifier si un abonnement existe entre un user et un client
    public function subscriptionExists($userId, $clientId) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM subscription 
            WHERE user_id = :userId AND client_id = :clientId
        ");
        $stmt->execute([
            ':userId' => $userId,
            ':clientId' => $clientId
        ]);
        return $stmt->fetchColumn() > 0;
    }
}
?>
