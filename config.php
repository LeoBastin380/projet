<?php
// Informations de connexion
$host = 'localhost'; // Ou l'IP du serveur MySQL
$dbname = 'simonbdd'; // Remplace par le nom de ta base
$username = 'root'; // Ton nom d'utilisateur MySQL
$password = ''; // Ton mot de passe MySQL (laisser vide si pas de mot de passe en local)

try {
    // Création de la connexion avec PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    // Configuration pour afficher les erreurs SQL
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // En cas d'erreur de connexion
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
