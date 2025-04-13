<?php
session_start(); // Démarrer la session
include 'config.php'; // Inclure la connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $username = trim($_POST["username"]);// trim c'est pour eliminer les espaces
    $password = $_POST["password"];

    try {
        // Vérifier si l'utilisateur existe
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            // Connexion réussie, enregistrer l'utilisateur en session
            $_SESSION["user_id"] = $user["id"];
            

            // Redirection vers home.php
            header("location: home.php");
        } else {
            // Identifiants incorrects
            die("Erreur : Nom d'utilisateur ou mot de passe incorrect.");
        }
    } catch (PDOException $e) {
        die("Erreur lors de la connexion : " . $e->getMessage());
    }
}
?>
