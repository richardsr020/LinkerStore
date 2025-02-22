<?php
require_once "../entity/DatabaseConnection.php";
require_once "../entity/Software.php";

class AddSoftwareController {

    // Définition des constantes pour les tailles maximales
    const MAX_ICON_SIZE = 5 * 1024 * 1024; // 5MB
    const MAX_FILE_SIZE = 80 * 1024 * 1024; // 80MB

    public function __construct() {
        // Vérification du rôle ADMIN
        session_start();
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'ADMIN') {
            echo json_encode(['success' => false, 'message' => 'You do not have permission to add software.']);
            exit;
        }
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = htmlspecialchars($_POST['name'] ?? '');
            $version = htmlspecialchars($_POST['version'] ?? '');
            $targetPlatform = htmlspecialchars($_POST['targetPlatform'] ?? '');
            $softwareIcon = $_FILES['softwareIcon'] ?? null;
            $compressedFile = $_FILES['compressedFile'] ?? null;

            // Vérification des champs requis
            if (empty($name) || empty($version) || empty($targetPlatform) || !$softwareIcon || !$compressedFile) {
                echo json_encode(['success' => false, 'message' => 'Please fill all fields and upload the required files.']);
                exit;
            }

            // Vérification de la plateforme cible
            $allowedPlatforms = ['Linux', 'Windows', 'Mac'];
            if (!in_array($targetPlatform, $allowedPlatforms)) {
                echo json_encode(['success' => false, 'message' => 'Invalid target platform selected.']);
                exit;
            }

            // Vérification de l'icône (type et taille)
            $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $iconExtension = strtolower(pathinfo($softwareIcon['name'], PATHINFO_EXTENSION));
            if (!in_array($iconExtension, $allowedImageExtensions)) {
                echo json_encode(['success' => false, 'message' => 'Only image files (jpg, jpeg, png, gif) are allowed for icons.']);
                exit;
            }
            if ($softwareIcon['size'] > self::MAX_ICON_SIZE) {
                echo json_encode(['success' => false, 'message' => 'Icon file size exceeds the limit of 5MB.']);
                exit;
            }

            // Vérification du fichier compressé (type et taille)
            $allowedFileExtensions = ['zip', 'rar'];
            $fileExtension = strtolower(pathinfo($compressedFile['name'], PATHINFO_EXTENSION));
            if (!in_array($fileExtension, $allowedFileExtensions)) {
                echo json_encode(['success' => false, 'message' => 'Only .zip or .rar files are allowed for software.']);
                exit;
            }
            if ($compressedFile['size'] > self::MAX_FILE_SIZE) {
                echo json_encode(['success' => false, 'message' => 'File size exceeds the limit of 80MB.']);
                exit;
            }

            // Définir les dossiers de destination
            $iconDir = '../../softwares/icons/';
            $softwareDir = '../../softwares/';
            
            // Créer les dossiers si nécessaire
            if (!is_dir($iconDir)) {
                mkdir($iconDir, 0755, true);
            }
            if (!is_dir($softwareDir)) {
                mkdir($softwareDir, 0755, true);
            }

            // Déplacer l'icône
            $iconFileName = uniqid('icon_') . '.' . $iconExtension;
            $iconPath = $iconDir . $iconFileName;
            if (!move_uploaded_file($softwareIcon['tmp_name'], $iconPath)) {
                echo json_encode(['success' => false, 'message' => 'Failed to upload icon.']);
                exit;
            }

            // Déplacer le fichier compressé
            $compressedFileName = uniqid('software_') . '.' . $fileExtension;
            $compressedFilePath = $softwareDir . $compressedFileName;
            if (!move_uploaded_file($compressedFile['tmp_name'], $compressedFilePath)) {
                echo json_encode(['success' => false, 'message' => 'Failed to upload software file.']);
                exit;
            }

            // Enregistrement dans la base de données
            $software = new Software();
            if ($software->create($name, $version, $targetPlatform, $iconFileName, $compressedFileName)) {
                echo json_encode(['success' => true, 'message' => 'Software added successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add software.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
        }
    }
}

// Instancier et exécuter le contrôleur
$controller = new AddSoftwareController();
$controller->handleRequest();
?>
