<?php
require "DatabaseConnection.php";
require "User.php";

$user = new User();
$loggedInUser = $user->authenticate("test@example.com", "MotDePasse123");

// Vérification de la connexion
if ($loggedInUser) {
    echo "Connexion réussie !";
    var_dump($loggedInUser);
} else {
    echo "Identifiants incorrects.";
}


?>
