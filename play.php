<?php
session_start();
include 'config.php';

if (!isset($_SESSION["user_id"])) {
    header("location: leave.php");
    exit;
}

$user_id = $_SESSION["user_id"];

// Récupérer les niveaux maximums pour chaque difficulté
$query = "SELECT d.id AS difficulte_id, d.nom AS difficulte, MAX(usn.id_niveau) AS max_niveau
          FROM difficulte d
          LEFT JOIN usn ON d.id = usn.id_difficulte AND usn.id_user = :user_id
          GROUP BY d.id";
$stmt = $conn->prepare($query);
$stmt->execute([':user_id' => $user_id]);
$difficultes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Simon</title>
    <link rel="stylesheet" href="play(CSS).css">
    <link rel="icon" type="image/x-icon" href="Icone.png">
</head>
<body>
    <div id="main">
        <h1>Jeu de Simon</h1>
        <p>Sélectionnez une difficulté et un niveau</p>

        <?php foreach ($difficultes as $difficulte): ?>
            <div class="difficulty-container">
                <div class="difficulty-header">
                    <?php echo htmlspecialchars($difficulte['difficulte']); ?>
                </div>

                <form action="game.php" method="GET">
                    <input type="hidden" name="difficulty" value="<?php echo htmlspecialchars($difficulte['difficulte']); ?>">
                    
                    <div class="levels-container">
                        <?php
                        $max_level = $difficulte['max_niveau'] ?? 1;
                        for ($i = 1; $i <= 30; $i++): 
                            $unlocked = $i <= $max_level;
                            $class = $unlocked ? 'level-button unlocked' : 'level-button locked';
                            $disabled = $unlocked ? '' : 'disabled';
                        ?>
                            <button type="submit" name="level_id" value="<?php echo $i; ?>" class="<?php echo $class; ?>" <?php echo $disabled; ?>>
                                <?php echo $i; ?>
                            </button>
                        <?php endfor; ?>
                    </div>
                </form>
            </div>
        <?php endforeach; ?>

        <div class="buttons-container">
            <button class="button" onclick="window.location.href='home.php'"><b>Leave</b></button>
        </div>
    </div>
</body>
</html>
