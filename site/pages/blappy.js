// Get the canvas element and its context
const canvas = document.getElementById("gameCanvas");
const ctx = canvas.getContext("2d");

// Ensure the canvas size is set correctly
canvas.width = 432;
canvas.height = 562;

// Load the background image
const bgImage = new Image();
bgImage.src = '../assets/bg.png'; // Make sure the path is correct
// Load Game Over image
const overImage = new Image();
overImage.src = '../assets/restart.png';

// Load the pipe image (pipe.png)
const pipeImage = new Image();
const pipeImageB = new Image();
pipeImage.src = '../assets/bot_pipe.png';
pipeImageB.src = '../assets/top_pipe.png';
// Pipe Variables
let pipeArray = [];
let pipeX = canvas.width;
let pipeY = canvas.height;

//Physics Variables
let velX = -2; // Pipes speed
let velY = 0; // Fird Blap
let gravity = 0.4;
let game_over = false;
let score = 0;
let start = false;

// Load the ground image
const groundImage = new Image();
groundImage.src = '../assets/ground.png';

// Load the bird images
const birdImages = [
    new Image(),
    new Image(),
    new Image()
];
birdImages[0].src = '../assets/bird1.png';
birdImages[1].src = '../assets/bird2.png';
birdImages[2].src = '../assets/bird3.png';

let currentBird = 0; // Bird Index
const birdSpeed = 100; // 100 ms change
let birdInterval;

// Load the bird image (bird1.png)
const birdImage = new Image();
birdImage.src = '../assets/bird1.png';
// Bird Variables
const birdX = canvas.width / 8; // Place the bird at the leftmost position (X = 0)
let birdY = (canvas.height - birdImage.height) / 2; // Center vertically in the canvas
const bird = {
    x: birdX,
    y: birdY,
    width: birdImage.width,
    height: birdImage.height
};

// Load Assets
window.onload = function() {
    // Draw Assets
    ctx.drawImage(bgImage, 0, 0, canvas.width, canvas.height);
    ctx.drawImage(groundImage, 0, canvas.height - groundImage.height + 80, canvas.width, groundImage.height);
    ctx.drawImage(birdImages[currentBird], birdX, birdY);

    requestAnimationFrame(update);
    setInterval(placePipes, 1750); // Every 1.8 sec

    document.addEventListener("keydown", moveFird);
    canvas.addEventListener("touchstart", moveFird);
}

// MAIN FUNCTION
function update() {
    if (game_over) {
        ctx.drawImage(overImage, (canvas.width / 2) - (overImage.width / 2), (canvas.height / 2) - (overImage.height / 2));
        birdStop();
        return;
    }
    if (start) {
        requestAnimationFrame(update);
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        // Update Canvas
        velY += gravity;
        birdY = Math.max(birdY + velY, 0);
        ctx.drawImage(bgImage, 0, 0, canvas.width, canvas.height);
        ctx.drawImage(birdImages[currentBird], birdX, birdY);

        if (birdY > canvas.height - groundImage.height + 40) {
            game_over = true;
        }

        // Pipes
        for (let i = 0; i < pipeArray.length; i++) {
            let pipe = pipeArray[i];
            pipe.x += velX;
            ctx.drawImage(pipe.img, pipe.x, pipe.y);

            if (!pipe.passed && bird.x > pipe.x + 78) {
                score += 0.5; //0.5 because there are 2 pipes! so 0.5*2 = 1, 1 for each set of pipes
                pipe.passed = true;
            }

            if (collision(birdX, birdY, pipe)) {
                game_over = true;
            }
        }
        ctx.drawImage(groundImage, 0, canvas.height - groundImage.height + 80, canvas.width, groundImage.height);

        // Score
        ctx.fillStyle = "white";
        ctx.font = "45px Monospace";
        const textWidth = ctx.measureText(score).width;
        const centeredX = (canvas.width - textWidth) / 2;
        ctx.fillText(score, centeredX, 45);

        // Clear Pipes
        while (pipeArray.length > 0 && pipeArray[0].x < -pipeX) {
            pipeArray.shift();
        }
    }
}

function birdAnimation() {
    birdInterval = setInterval(() => {
        currentBird = (currentBird + 1) % birdImages.length;
    }, birdSpeed);
}

function birdStop() {
    clearInterval(birdInterval);
}

function placePipes() {
    if (game_over) {
        return;
    } else {
        let rand_pipeY = pipeY - pipeImage.height / 4 -
            Math.random() * (pipeImage.height / 2);
        let hole = canvas.height / 4;
        let bottom_pipe = {
            img: pipeImage,
            x: pipeX,
            y: rand_pipeY,
            passed: false
        }
        pipeArray.push(bottom_pipe);

        let top_pipe = {
            img: pipeImageB,
            x: pipeX,
            y: rand_pipeY - pipeImage.height - hole,
            passed: false
        }
        pipeArray.push(top_pipe);
    }
}

function moveFird(e) {
    if (e.code == "Space" || e.type == "touchstart") {
        // Reset game
        if (game_over) {
            birdY = (canvas.height - birdImages[currentBird].height) / 2; // Reset bird's position
            pipeArray = []; // Clear all pipes
            score = 0; // Reset score
            velY = 0; // Reset bird's velocity
            game_over = false;
            requestAnimationFrame(update);
            birdAnimation();
        }
        if (!start) {
            start = true;
            requestAnimationFrame(update);
            birdAnimation();
        }
        if (start) {
            // Blap 
            velY = -8;
        }
    }

}

function collision(birdX, birdY, pipe) {
    const birdWidth = birdImages[currentBird].width;
    const birdHeight = birdImages[currentBird].height;
    const pipeWidth = pipe.img.width;
    const pipeHeight = pipe.img.height;

    return birdX < pipe.x + pipeWidth &&
        birdX + birdWidth > pipe.x &&
        birdY < pipe.y + pipeHeight &&
        birdY + birdHeight > pipe.y;
}