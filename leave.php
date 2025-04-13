<?php
session_start(); // Démarrer la session

// Détruire la session
session_destroy();

// Rediriger vers la page de connexion ou d'accueil
header("Location: Projet(html).php");
exit;
?>