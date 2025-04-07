<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escape Room Challenge</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Creepster&family=Poppins:wght@400;600&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #1a202c;
            color: white;
        }
        .timer {
            font-family: 'Creepster', cursive;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">
    <header class="bg-gray-800 p-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold text-red-500 hover:text-red-400 transition-colors duration-300">
                <h1 class="flex items-center">
                    <i class="fas fa-key mr-2"></i> ESCAPE ROOM
                </h1>
            </a>
            <div class="timer bg-black p-2 rounded-lg text-red-500 text-xl">
                <?php
                if (isset($_SESSION['game_started'])) {
                    $timeLeft = TOTAL_TIME - (time() - $_SESSION['game_started']);
                    echo "<i class='fas fa-clock'></i> " . gmdate("i:s", $timeLeft);
                }
                ?>
            </div>
        </div>
    </header>
    <main class="flex-grow container mx-auto p-4">