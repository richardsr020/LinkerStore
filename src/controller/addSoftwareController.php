<?php
require_once "../entity/DatabaseConnection.php";
require_once "../entity/Software.php";


class AddSoftwareController {

    public function __construct() {
        // Vérification du rôle ADMIN
        session_start();
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'ADMIN') {
            echo json_encode(['success' => false, 'message' => 'You do not have permission to add software.']);
            exit;
        }
    }

    public function handleRequest() {
        // Vérification si la requête est bien en POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $version = $_POST['version'] ?? '';
            $file = $_FILES['softwareFile'] ?? null;

            if (empty($name) || empty($version) || !$file) {
                echo json_encode(['success' => false, 'message' => 'Please fill all fields and upload a file.']);
                exit;
            }

            // Vérifications du fichier (type, taille, extension)
            $allowedExtensions = ['zip', 'rar'];
            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
            if (!in_array($fileExtension, $allowedExtensions)) {
                echo json_encode(['success' => false, 'message' => 'Only .zip or .rar files are allowed.']);
                exit;
            }

            // Vérification de la taille du fichier (par exemple max 20MB)
            if ($file['size'] > 80 * 1024 * 1024) {
                echo json_encode(['success' => false, 'message' => 'File size exceeds the limit of 20MB.']);
                exit;
            }

            // Déplacer le fichier dans le dossier des logiciels
            $targetDir = '../../softwares/';
            $targetFile = $targetDir . basename($file['name']);

            if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
                echo json_encode(['success' => false, 'message' => 'Error uploading the file.']);
                exit;
            }

            // Créer une instance de la classe Software pour ajouter le logiciel dans la base de données
            $software = new Software();
            if ($software->create($name, $version)) {
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
