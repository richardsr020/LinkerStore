<?php
require_once "../entity/DatabaseConnection.php";
require_once "../entity/User.php";
require_once "../entity/Client.php";
require_once "../entity/Subscription.php";

class DashboardController extends DatabaseConnection {

    public function __construct() {
        parent::__construct();
    }

    public function getDashboardData() {
        session_start();
        
        // Vérifier si l'utilisateur est authentifié
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'User not authenticated']);
            exit;
        }

        $userId = $_SESSION['user_id'];  // ID utilisateur dans la session
        
        // Charger les données de l'utilisateur
        $user = new User();
        $userData = $user->getUserById($userId);

        if (!$userData) {
            echo json_encode(['error' => 'User data not found']);
            exit;
        }

        // // Charger les abonnements de l'utilisateur
        // $subscription = new Subscription();
        // $subscriptions = $subscription->getByUserId($userId);

        // if (!$subscriptions) {
        //     echo json_encode(['error' => 'No subscriptions found for the user']);
        //     exit;
        // }

        // // Charger les clients associés à l'utilisateur via les abonnements
        // $client = new Client();
        // $clientsData = [];
        // foreach ($subscriptions as $sub) {
        //     $clientData = $client->getById($sub['client_id']);
        //     if ($clientData) {
        //         $clientsData[] = $clientData;
        //     }
        // }

        // Organiser les données pour le dashboard
        $dashboardData = [
            'user' => $userData,
            'subscriptions' => $subscriptions,
            'clients' => $clientsData
        ];

        // Retourner les données sous forme de JSON
        //print_r($dashboardData);
        echo json_encode($dashboardData);
    }
}

// Création du contrôleur et appel de la méthode pour récupérer les données
$dashboardController = new DashboardController();
$dashboardController->getDashboardData();
?>
