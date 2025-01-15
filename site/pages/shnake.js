const canvas = document.getElementById("gameCanvas");
const ctx = canvas.getContext("2d");

// Make the canvas a square
const size = Math.min(window.innerWidth, window.innerHeight) * 0.9;
canvas.width = size;
canvas.height = size;
const blockX = canvas.width / 20;
const blockY = canvas.height / 20;

// Optional: Center the canvas on the screen   
canvas.style.border = '5px solid gray';
canvas.style.backgroundColor = 'black';

// Game Variables
let start = false;
let key = 0; // Decides which direction the snake goes 
let score = 1;

// Food Variables
let foodX = Math.floor(Math.random() * 20);
let foodY = Math.floor(Math.random() * 20);
let food = {
    x: foodX * blockX,
    y: foodY * blockY,
    width: blockX,
    height: blockY,
    color: "red"
};

// Snake Variables
let snake = [{ x: Math.floor(10) * blockX, y: Math.floor(10) * blockY }];

// Draw Food
function drawFood() {
    ctx.fillStyle = food.color;
    ctx.fillRect(food.x, food.y, food.width, food.height);
}

// Draw Snake
function drawSnake() {
    ctx.fillStyle = 'green';
    snake.forEach(segment => {
        ctx.fillRect(segment.x, segment.y, blockX, blockY);
    });
}

// Move Snake
function moveSnake() {
    const head = {...snake[0] }; // Copy the current head position

    if (key === 1) head.y -= blockY; // Move up
    if (key === 2) head.y += blockY; // Move down
    if (key === 3) head.x -= blockX; // Move left
    if (key === 4) head.x += blockX; // Move right

    snake.unshift(head); // Add new head to the snake

    // Check if the snake eats food
    if (eatFood(head.x, head.y, food.x, food.y)) {
        // Reposition food randomly
        score += 1;
        food.x = Math.floor(Math.random() * 20) * blockX;
        food.y = Math.floor(Math.random() * 20) * blockY;
    } else {
        snake.pop(); // Remove the tail if no food is eaten
    }
}

// Check Collision
function collision() {
    const head = snake[0];
    // Check if the head is outside the canvas or hits the body
    return (
        head.x < 0 || head.y < 0 || // Out of bounds check
        head.x >= canvas.width || head.y >= canvas.height || // Out of bounds check
        snake.slice(1).some(segment => segment.x === head.x && segment.y === head.y) // Hits body check
    );
}

// Check Eat Food
function eatFood(snakeX, snakeY, foodX, foodY) {
    return (
        snakeX < foodX + blockX &&
        snakeX + blockX > foodX &&
        snakeY < foodY + blockY &&
        snakeY + blockY > foodY
    );
}

// Main Function
function update() {
    setTimeout(() => {
        if (collision()) {
            sendScoreToServer(score);
            document.location.reload();
        }
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        moveSnake();
        moveButton();
        drawSnake();
        drawFood();

        requestAnimationFrame(update);
    }, 130);
}

// Key Listener
window.onload = function() {
    document.addEventListener("keydown", snakeMove);
    drawFood();
    drawSnake();
    requestAnimationFrame(update);
};

// Move Snake with Buttons
function moveButton() {
    document.getElementById("up").addEventListener("click", () => {
        if (key !== 2) {
            key = 1;
        };
    });
    document.getElementById("down").addEventListener("click", () => {
        if (key !== 1) {
            key = 2;
        };
    });
    document.getElementById("left").addEventListener("click", () => {
        if (key !== 4) {
            key = 3;
        };
    });
    document.getElementById("right").addEventListener("click", () => {
        if (key !== 3) {
            key = 4;
        };
    });
}

// Move Snake with Keyboard arrows
function snakeMove(e) {
    // Prevent the snake from turning 180 degrees (going back on itself)
    if (e.code === "ArrowUp" && key !== 2) key = 1;
    if (e.code === "ArrowDown" && key !== 1) key = 2;
    if (e.code === "ArrowLeft" && key !== 4) key = 3;
    if (e.code === "ArrowRight" && key !== 3) key = 4;
}

// Update Top Score in DB
function sendScoreToServer(score) {
    let playerName = prompt("Your Score: " + score + "\nPlease Enter Your Name");
    // Check if the user provided a name
    if (playerName && playerName.trim() !== "") {
        // Send the score and name to PHP using fetch
        fetch('../posts/post_score.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `score=${score}&name=${encodeURIComponent(playerName)}`
            })
            .then(response => response.text())
            .then(data => {
                console.log('Server response:', data);
            })
            .catch(error => {
                console.error('Error:', error);
            });
    } else {
        alert("Name was required to save your score.\nScore Lost!");
    }
    document.location.reload();
}