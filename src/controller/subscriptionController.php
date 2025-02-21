<?php
require_once "Subscription.php";

class SubscriptionController {
    private $subscription;

    public function __construct() {
        $this->subscription = new Subscription();
    }

    // Fonction pour créer un abonnement
    public function createSubscription() {
        header('Content-Type: application/json');

        // Récupération des données JSON envoyées
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($data['email'], $data['quota'])) {
            echo json_encode(["success" => false, "message" => "Données invalides."]);
            exit;
        }
        
        $email = $data['email'];
        $quota = $data['quota'];
        
        // Trouver le client par email
        $client = $this->subscription->getClientByEmail($email);
        
        if (!$client) {
            echo json_encode(["success" => false, "message" => "Client non trouvé."]);
            exit;
        }
        
        $clientId = $client['id'];
        $userId = $this->getAuthenticatedUserId(); // Supposé récupérer l'ID de l'utilisateur connecté
        
        $success = $this->subscription->create($userId, $clientId, $quota);

        echo json_encode(["success" => $success, "message" => $success ? "Abonnement créé." : "Échec de la création."]);
    }

    // Fonction qui reçoit client_id et quota et retourne true (logique à implémenter plus tard)
    public function processSubscription($clientId, $quota) {
        return true;
    }

    // Fonction supposée récupérer l'ID de l'utilisateur connecté
    private function getAuthenticatedUserId() {
        // À implémenter selon le système d'authentification utilisé
        return 1; // Valeur temporaire
    }
}

// Routeur basique
$controller = new SubscriptionController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->createSubscription();
}
