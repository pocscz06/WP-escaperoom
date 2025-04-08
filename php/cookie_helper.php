<?php

/**
 * Save progress to a cookie.
 *
 * @param array $session_data The session data to save.
 */
function save_progress_to_cookie($session_data) {
    if (!empty($session_data) && isset($session_data['current_puzzle']) && $session_data['current_puzzle'] > 0) {
        setcookie('escape_room_progress', json_encode($session_data), time() + (86400 * 7), "/"); // 7 days
    }
}

/**
 * Load progress from a cookie.
 *
 * @return array|null The saved progress data or null if no valid cookie exists.
 */
function load_progress_from_cookie() {
    if (isset($_COOKIE['escape_room_progress'])) {
        $saved_progress = json_decode($_COOKIE['escape_room_progress'], true);
        if (!empty($saved_progress) && isset($saved_progress['current_puzzle']) && $saved_progress['current_puzzle'] > 0) {
            return $saved_progress;
        }
    }
    return null;
}

/**
 * Clear the progress cookie.
 */
function clear_progress_cookie() {
    if (isset($_COOKIE['escape_room_progress'])) {
        setcookie('escape_room_progress', '', time() - 3600, "/"); // Expire the cookie
    }
}
?>