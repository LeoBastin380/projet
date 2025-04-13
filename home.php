<?php
session_start();
include 'config.php';



if (!isset($_SESSION["user_id"])) {
    header("location: leave.php");
}

?>
<!DOCTYPE html>
<html>
    <head><!-- Test de commentaire -->
        <meta charset="utf-8" />
        <title>Simon</title>
        <link rel="icon" type="image/x-icon" href="Icone.png">
        <link rel = 'stylesheet' href ='home(CSS).css'>

    </head>
    <body>
        <div class= "container">
            <h1>Jeu de Simon</h1>
            <button type="submit" onclick="window.location.href='play.php'" class="button"><b>Play</b></button>
            <button type="submit" onclick="window.location.href='leave.php'" class="button"><b>Settings</b></button>
            <button type="submit" onclick="window.location.href='leave.php'"><b>Leave</b></button>

        </div>
    </body>
</html>