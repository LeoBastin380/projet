<?php
// Inclure le fichier de connexion à la base de données
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $username = trim($_POST["new_username"]);// trim est utilise pour retirer les espaces
    $password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    // Vérifier que les mots de passe correspondent
    if ($password !== $confirm_password) {
        die("Erreur : Les mots de passe ne correspondent pas.");
    }

    try {
        // Vérifier si l'utilisateur existe déjà
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        //Associer les niveaux et la difficulté pour l'utilisateur
        
        if ($stmt->rowCount() > 0) {
            die("Erreur : Ce nom d'utilisateur est déjà pris.");
        }

        // Hacher le mot de passe
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insérer l'utilisateur dans la base de données
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->execute(['username' => $username, 'password' => $hashed_password]);

        // Récupérer l'ID de l'utilisateur nouvellement créé
        $user_id = $conn->lastInsertId();

        // Insérer l'utilisateur au niveau 1 pour toutes les difficultés
        $stmt = $conn->prepare("INSERT INTO usn (id_user, id_niveau, id_difficulte) VALUES (:id_user, 1, :id_difficulte)");
        
        foreach ([1, 2, 3] as $difficulty_id) { // 1 = easy, 2 = medium, 3 = hard
            $stmt->execute(['id_user' => $user_id, 'id_difficulte' => $difficulty_id]);
        }

        // Affichage du message et redirection
        echo "<div style='text-align: center; margin-top: 20px; font-size: 18px; color: cyan;'>
                Le compte a été créé !
              </div>";
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'Projet(html).php';
                }, 5000);
              </script>";
        exit();

    } catch (PDOException $e) {
        die("Erreur lors de l'inscription : " . $e->getMessage());
    }
}
?>
