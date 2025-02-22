class Software extends DatabaseConnection {

    public function __construct() {
        parent::__construct();
    }

    // 🔹 Ajouter un logiciel avec icône et fichier compressé
    public function create($name, $version, $targetPlatform, $iconFileName, $compressedFileName) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO software (name, version, target_platform, icon, compressed_file) 
             VALUES (:name, :version, :targetPlatform, :icon, :compressedFile)"
        );
        return $stmt->execute([
            ':name' => $name,
            ':version' => $version,
            ':targetPlatform' => $targetPlatform,
            ':icon' => $iconFileName,
            ':compressedFile' => $compressedFileName
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
    public function update($id, $name, $version, $targetPlatform, $iconFileName, $compressedFileName) {
        $stmt = $this->pdo->prepare(
            "UPDATE software SET name = :name, version = :version, target_platform = :targetPlatform, icon = :icon, compressed_file = :compressedFile WHERE id = :id"
        );
        return $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':version' => $version,
            ':targetPlatform' => $targetPlatform,
            ':icon' => $iconFileName,
            ':compressedFile' => $compressedFileName
        ]);
    }

    // 🔹 Supprimer un logiciel
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM software WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
