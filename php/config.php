<?php
// Initialize session and define game constants
session_start();
define("TOTAL_TIME", 600); // 10 minutes in seconds
define("MAX_HINTS", 3);
define("TOTAL_PUZZLES", 3);

// Reset game state if starting fresh
if (!isset($_SESSION['game_started'])) {
    $_SESSION['game_started'] = time();
    $_SESSION['hints_used'] = 0;
    $_SESSION['completed_puzzles'] = [];
    $_SESSION['current_puzzle'] = 1;
}
?>