<?php
session_start();
require_once "../entity/DatabaseConnection.php";
require_once "../entity/User.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = [];
    
    // Crée une instance de la classe User
    $userModel = new User();

    // 🔹 Récupération des données
    $name = trim($_POST['name'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $phone = filter_var(trim($_POST['phone'] ?? ''), FILTER_SANITIZE_STRING);
    $password = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');

    // 🔹 Validation des données
    if (empty($name)) {
        $errors[] = "Name is required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (!preg_match('/^\+?[0-9]{7,15}$/', $phone)) {
        $errors[] = "Invalid phone number.";
    }

    if (strlen($password) < 8 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $errors[] = "Password must be at least 8 characters long and include both letters and numbers.";
    }

    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    // 🔹 Vérifier si l'email ou le téléphone existe déjà
    if ($userModel->emailExists($email)) {
        $errors[] = "Email is already registered.";
    }

    if ($userModel->phoneExists($phone)) {
        $errors[] = "Phone number is already registered.";
    }

    // 🔹 Gérer les erreurs
    if (!empty($errors)) {
        echo json_encode(["success" => false, "errors" => $errors]);
        exit;
    }

    // 🔹 Créer un nouvel utilisateur
    $userId = $userModel->createUser($email, $phone, $password);

    if ($userId) {
        echo json_encode(["success" => true, "message" => "Registration successful."]);
    } else {
        echo json_encode(["success" => false, "errors" => ["Error during registration."]]);
    }
    exit;
}

echo json_encode(["success" => false, "errors" => ["Invalid request."]]);
exit;
?>
