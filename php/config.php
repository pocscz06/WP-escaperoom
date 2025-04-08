<?php
session_start();

// Persist session ID in a cookie for progress tracking
if (!isset($_COOKIE['escape_room_session'])) {
    setcookie('escape_room_session', session_id(), time() + (86400 * 7), "/"); // 7 days
}

// Define game constants
define("TOTAL_TIME", 600); // 10 minutes in seconds
define("MAX_HINTS", 3);
define("TOTAL_PUZZLES", 3);

// Reset game state if starting fresh
if (isset($_POST['start_game'])) {
    $_SESSION['game_started'] = time();
    $_SESSION['hints_used'] = 0;
    $_SESSION['completed_puzzles'] = [];
    $_SESSION['current_puzzle'] = 1;
} elseif (!isset($_SESSION['hints_used'])) { // Ensure session vars exist
    $_SESSION['hints_used'] = 0;
    $_SESSION['completed_puzzles'] = [];
    $_SESSION['current_puzzle'] = 1;
}

// Save progress in cookies only if the session is active
if (!empty($_SESSION) && isset($_SESSION['current_puzzle']) && $_SESSION['current_puzzle'] > 0) {
    setcookie('escape_room_progress', json_encode($_SESSION), time() + (86400 * 7), "/"); // 7 days
} else {
    // Clear the progress cookie if no valid session exists
    if (isset($_COOKIE['escape_room_progress'])) {
        setcookie('escape_room_progress', '', time() - 3600, "/"); // Expire the cookie
    }
}
?>