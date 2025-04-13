<?php
session_start();
include 'config.php';

if (!isset($_SESSION["user_id"])) {
    header("location: leave.php");
    exit;
}

$user_id = $_SESSION["user_id"];

// Récupérer la difficulté et le niveau depuis les paramètres GET
$difficulty = isset($_GET['difficulty']) ? $_GET['difficulty'] : 'easy';
$level_id = isset($_GET['level_id']) ? (int)$_GET['level_id'] : 1;

// Vérifie que difficulty est bien une valeur autorisée
$allowed_difficulties = ['easy', 'medium', 'hard'];
if (!in_array($difficulty, $allowed_difficulties)) {
    $difficulty = 'easy'; // Si la difficulté n'est pas reconnue on met easy
}

// Récupérer l'ID de la difficulté à partir de la base de données
$query = "SELECT id FROM difficulte WHERE nom = :difficulty";
$stmt = $conn->prepare($query);
$stmt->execute([':difficulty' => $difficulty]);
$difficulty_data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$difficulty_data) {
    die("La difficulté spécifiée n'existe pas.");
}

$difficulty_id = $difficulty_data['id'];

// Récupérer le niveau maximal débloqué pour l'utilisateur et la difficulté
$query = "SELECT MAX(id_niveau) AS max_niveau FROM usn WHERE id_user = :user_id AND id_difficulte = :difficulty_id";
$stmt = $conn->prepare($query);
$stmt->execute([':user_id' => $user_id, ':difficulty_id' => $difficulty_id]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Définir le niveau maximal accessible
$max_level = ($result['max_niveau'] !== null) ? (int)$result['max_niveau'] : 1;

// S'assurer que le niveau actuel demandé est valide
if ($level_id > $max_level) {
    $level_id = $max_level; // Ramener au niveau maximal si on essaie d'aller plus loin
}

// Récupérer les infos du niveau actuel
$query = "SELECT * FROM niveau WHERE id = :level_id";
$stmt = $conn->prepare($query);
$stmt->execute([':level_id' => $level_id]);
$level = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$level) {
    die("Le niveau spécifié n'existe pas.");
}

// Gestion des dimensions des boutons en fonction de la difficulté
$gridConfig = [
    'easy' => ['rows' => 2, 'cols' => 2],
    'medium' => ['rows' => 2, 'cols' => 3],
    'hard' => ['rows' => 3, 'cols' => 3]
];

$gridSize = $gridConfig[$difficulty];
$rows = $gridSize['rows'];
$cols = $gridSize['cols'];

// Liste de couleurs uniques
$colors = ['red', 'blue', 'green', 'purple', 'orange', 'cyan', 'lime','pink','navy','gold','grey'];
shuffle($colors);




if(isset($_GET['last-level-input']) && $_GET['last-level-input'] == 'true') {
    // Récupérer le niveau suivant
    $query = "SELECT * FROM niveau WHERE id = :level_id";
    $stmt = $conn->prepare($query);
    $stmt->execute([':level_id' => $level_id + 1]);
    $level = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si un niveau a été trouvé
    if ($level) {

        // Mettre à jour la table usn
        $sql = "UPDATE usn SET id_niveau = :id_level WHERE id_user = :id_user AND id_difficulte = :id_difficulte";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_user', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':id_level', $level['id'], PDO::PARAM_INT);
        $stmt->bindParam(':id_difficulte', $difficulty_id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: play.php");
        exit;

    } else {
        echo "Niveau non trouvé.";
    }
}
?>
<!DOCTYPE html>
<html>
    
<head>
    <meta charset="utf-8">
    <title>Simon</title>
    <link rel="stylesheet" href="game.css">
    <link rel="icon" type="image/x-icon" href="Icone.png">
</head>
<body>
    <div class="container">
        <h1>Jeu de Simon</h1>
        <div class="Box_de_difficulte">
            DIFFICULTÉ : <?php echo htmlspecialchars(ucfirst($difficulty)); ?> | NIVEAU : <?php echo htmlspecialchars($level_id); ?>
        </div>

        <!-- Zone de jeu -->
        <div class="game-area">
            <div class="button-grid" style="--rows: <?php echo $rows; ?>; --cols: <?php echo $cols; ?>;">
                <?php for ($i = 0; $i < $rows * $cols; $i++): ?>
                    <button class="color-button" 
                            data-index="<?php echo $i; ?>" 
                            style="background-color: <?php echo htmlspecialchars($colors[$i % count($colors)]); ?>;">
                    </button>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Boutons de contrôle -->
        <div class="controls">
            <input id="max_level" name="max_level" type="hidden" value="<?php echo $max_level ?>" />
            <button id="start-btn" style="background-color:cyan;" class="button"onclick>Start</button>
            <button onclick="window.location.href='play.php';" class="button">Leave</button>
            <button onclick="goToPreviousLevel()" class="button">Previous</button>

            <?php if ($level_id < $max_level): ?>
                <button id="next-level-btn" style="display: none;" onclick="goToNextLevel()" class="button">Next Level</button>
            <?php endif; ?>

            <?php if ($level_id == $max_level): ?>
                <form id="level-form" method="GET" style="display: none;" action="game.php">
                    <input id="difficulty" name="difficulty" type="hidden" value="<?php echo $difficulty ?>" />
                    <input id="level_id" name="level_id" type="hidden" value="<?php echo $level_id + 1 ?>" />
                    <input id="last-level-input" name="last-level-input" type="hidden" value="true" />
                    <button id="last-level-btn" class="button">Next Level</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
    

    <script src='game.js'></script>
    
</body>
</html>
