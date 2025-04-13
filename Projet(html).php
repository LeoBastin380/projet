<?php ?>
<!DOCTYPE html>
<html>
    <head><!-- Test de commentaire -->
        <meta charset="utf-8" />
        <title>Simon</title>
        <link rel="icon" type="image/x-icon" href="Icone.png">
        <link rel = 'stylesheet' href ='Projet(CSS).css'>

    </head>
    <body>
        <div class="container">
            <h1>Jeu de Simon</h1>
    
            <!-- Formulaire de connexion -->
            <form id="loginForm" action="login.php" method="POST">
                <h2>Connexion</h2>
                <input type="text" name="username" placeholder="Nom d'utilisateur" required>
                <input type="password" name="password" placeholder="Mot de passe" required>
                <button type="submit">Se connecter</button>
            </form>
            <!-- Formulaire de création de compte -->
            <form id="registerForm" action="register.php" method="POST">
                <h2>Créer un compte</h2>
                <input type="text" name="new_username" placeholder="Prénom.Nom" required>
                <input type="password" name="new_password" placeholder="Mot de passe" required>
                <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
                <button type="submit">Créer un compte</button>
            </form>
        </div>
        
        
    </body>
</html>