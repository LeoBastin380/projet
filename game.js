let sequence = [];
let playerSequence = [];
let levelSpeed;
let isPlayerTurn = false;

const startBtn = document.getElementById('start-btn');
const nextLevelBtn = document.getElementById('next-level-btn');
const nextLevelForm = document.getElementById('level-form');
const buttons = document.querySelectorAll('.color-button');

//  Récupérer la difficulté actuelle depuis l'URL
function getDifficultyFromURL() {
    const params = new URLSearchParams(window.location.search);
    return params.get('difficulty') || 'easy';  // 'easy' par défaut si non défini
}

//  Récupérer le niveau actuel depuis l'URL
function getLevelFromURL() {
    const params = new URLSearchParams(window.location.search);
    return parseInt(params.get('level_id'), 10) || 1;  // 1 par défaut si non défini
}

//  Définir la vitesse en fonction du niveau et de la difficulté
function setLevelSpeed(level, difficulty) {
    let speedFactor;
    if (difficulty === 'easy') {
        speedFactor = 10;
    } else if (difficulty === 'medium') {
        speedFactor = 20;
    } else if (difficulty === 'hard') {
        speedFactor = 25;
    }

    return Math.max(0, 1000 - level * speedFactor);
}

//  Récupérer les valeurs depuis l'URL
let difficulty = getDifficultyFromURL();
let level = getLevelFromURL();
let Maxlevel = document.getElementById('max_level').value;
levelSpeed = setLevelSpeed(level, difficulty);

//  Vérification dans la console
console.log(`Difficulté: ${difficulty}, Niveau: ${level}, Vitesse: ${levelSpeed}`);

//  Générer une nouvelle séquence pour le niveau
function generateSequence(length) {
    sequence = [];
    for (let i = 0; i < length; i++) {
        sequence.push(Math.floor(Math.random() * buttons.length));
    }
}
// Jouer la séquence pour montrer au joueur
function playSequence() {
    let index = 0;
    isPlayerTurn = false;

    function activateButton() {
        if (index >= sequence.length) {
            isPlayerTurn = true; // Permet au joueur de commencer
            return;
        }

        const buttonIndex = sequence[index];
        const button = buttons[buttonIndex];
        
        buttonColor = button.style.backgroundColor;
        if (sounds[buttonColor]) {
            playSound(sounds[buttonColor]);
        }

        button.classList.add('active');
        setTimeout(() => {
            button.classList.remove('active');
            index++;
            setTimeout(activateButton, levelSpeed / 2); // Ajoute un petit délai entre les activations
        }, levelSpeed / 2);
    }

    activateButton();
}

// Lancer ou redémarrer le jeu
startBtn.addEventListener('click', () => {
    startBtn.textContent = 'Restart';
    
    if (playerSequence.length !== sequence.length) {
        startBtn.disabled = true;
    }
    
    playerSequence = [];
    generateSequence(level);
    levelSpeed = setLevelSpeed(level, difficulty);
    playSequence();
});

// Vérifier si le joueur a correctement suivi la séquence
function checkPlayerInput(index) {
    if (playerSequence[index] !== sequence[index]) {
        alert('Vous avez perdu ! Cliquez sur Restart pour réessayer.');
        startBtn.textContent = 'Restart';
        isPlayerTurn = false;
        return;
    }

    if (playerSequence.length === sequence.length) {
        alert('Bravo ! Vous avez gagné ce niveau.');
        if (level == Maxlevel) {
            nextLevelForm.style.display = 'inline-block';
        } else {
            nextLevelBtn.style.display = 'inline-block';
        }
    }
}








// Gestion des clics sur les boutons colorés
buttons.forEach((button, index) => {
    button.addEventListener("click", () => {
        if (!isPlayerTurn) return;

        playerSequence.push(index);
        button.classList.add("active");

        // Récupérer la couleur du bouton et jouer le son
        buttonColor = button.style.backgroundColor;
        if (sounds[buttonColor]) {
            playSound(sounds[buttonColor]);
        }

        setTimeout(() => button.classList.remove("active"), levelSpeed / 2);
        checkPlayerInput(playerSequence.length - 1);
    });
});








// Lancer ou redémarrer le jeu
startBtn.addEventListener('click', () => {
    startBtn.textContent = 'Restart';
    //rajouter une condition pour ne pas pouvoir restart une sequence si la première n'est pas finie.
    if(playerSequence.length !== sequence.length)
    {
        startBtn("#comment_sub").attr("disabled", false);
    }
    playerSequence = [];
    sequence.push(Math.floor(Math.random() * buttons.length));
    levelSpeed = setLevelSpeed(sequence.length);
    playSequence();
});

// Aller au niveau précédent
function goToPreviousLevel() {
    const previousLevel = Math.max(1, level - 1);
    window.location.href = `game.php?difficulty=${encodeURIComponent(difficulty)}&level_id=${previousLevel}`;
}

// Aller au niveau suivant
function goToNextLevel() {
    const nextLevel = level + 1;
    window.location.href = `game.php?difficulty=${encodeURIComponent(difficulty)}&level_id=${nextLevel}`;
}

// Son qui fonctionne aux cliques

// son associé à une couleur
const sounds = {
    red: "son/sola.wav",
    blue: "son/doa.wav",
    green: "son/fa#.wav",
    purple: "son/fa.wav",
    orange: "son/faa.wav",
    cyan: "son/la.wav",
    lime: "son/mi.wav",
    pink: "son/ré.wav",
    navy: "son/réa.wav",
    gold: "son/si.wav",
    grey: "son/sib.wav",
};

// Fonction pour jouer un son
function playSound(sound) {
    audio = new Audio(sound);
    audio.play();
}






/*TEST DE CODE */
/*TEST POUR QUE LA SEQUENCE JOUE LE SON ASSOCIE*/












/*playSound('red');
document.addEventListener("click", () => {
    playSound('blue');
});*/