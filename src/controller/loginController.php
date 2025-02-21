<?php
session_start();
header('Content-Type: application/json');

require_once "../entity/DatabaseConnection.php";
require_once "../entity/User.php";

$response = ['success' => false];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['error' => "Invalid email format"]);
        exit;
    }

    // Créer une instance de la classe User
    $userModel = new User();

    // Vérifier les informations d'identification
    $user = $userModel->authenticate($email, $password);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['isLoggedIn'] = true;
        $response['success'] = true;
    } else {
        $response['error'] = "Invalid email or password";
    }
}

echo json_encode($response);
?>
