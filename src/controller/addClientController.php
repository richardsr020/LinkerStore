<?php
session_start(); // Start session for user authentication check

require_once "../entity/Client.php";
$client = new Client();

$response = ["success" => false, "message" => "An error occurred."];

// 🔹 Check if the user is logged in
if (!isset($_SESSION["isLoggedIn"]) || $_SESSION["isLoggedIn"] !== true) {
    $response["message"] = "User is not authenticated.";
    echo json_encode($response);
    exit;
}

// 🔹 Check required POST data
if (!isset($_POST["name"]) || !isset($_POST["email"]) || !isset($_POST["phone"])) {
    $response["message"] = "Missing required fields.";
    echo json_encode($response);
    exit;
}

$name = trim($_POST["name"]);
$email = trim($_POST["email"]);
$phone = trim($_POST["phone"]);

// 🔹 Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response["message"] = "Invalid email format.";
    echo json_encode($response);
    exit;
}

// 🔹 Check if email already exists
if ($client->emailExists($email)) {
    $response["message"] = "This email is already registered.";
    echo json_encode($response);
    exit;
}

// 🔹 Vérification si le téléphone existe déjà
if ($client->phoneExists($phone)) {
    $response["message"] = "This phone number is already registered.";
    echo json_encode($response);
    exit;
}

// 🔹 Check if a file was uploaded
if (!isset($_FILES["publicKeyFile"]) || $_FILES["publicKeyFile"]["error"] !== UPLOAD_ERR_OK) {
    $response["message"] = "Please upload a valid file.";
    echo json_encode($response);
    exit;
}

// 🔹 Validate file extension (.txt only)
$fileName = $_FILES["publicKeyFile"]["name"];
$fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

if ($fileExtension !== "txt") {
    $response["message"] = "Only .txt files are allowed!";
    echo json_encode($response);
    exit;
}

// 🔹 Set upload directory and create it if it doesn't exist
$uploadDir = "../../uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true); // Create directory with recursive option
}

// 🔹 Move file to the upload directory
$filePath = $uploadDir . basename($fileName);

if (!move_uploaded_file($_FILES["publicKeyFile"]["tmp_name"], $filePath)) {
    $response["message"] = "Failed to save the file.";
    echo json_encode($response);
    exit;
}

// 🔹 Read public key content
$publicKey = file_get_contents($filePath);
unlink($filePath); // Delete file after processing

// 🔹 Add client to the database
if ($client->create($name, $email, $phone, $publicKey)) {
    $response = [
        "success" => true,
        "message" => "Client added successfully!"
    ];
} else {
    $response["message"] = "Failed to add client.";
}

echo json_encode($response);
exit;
?>
