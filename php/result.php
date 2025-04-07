<?php
require_once 'config.php';

// Check if all puzzles were completed
$allPuzzlesSolved = count($_SESSION['completed_puzzles'] ?? []) >= TOTAL_PUZZLES;
$timeLeft = TOTAL_TIME - (time() - $_SESSION['game_started']);

// Determine game outcome
$success = $allPuzzlesSolved && $timeLeft > 0;

// Reset game session
session_unset();
session_destroy();

require_once 'header.php';
?>

<div class="max-w-3xl mx-auto py-12 text-center">
    <?php if ($success): ?>
        <div class="bg-green-900 border-2 border-green-400 rounded-lg p-8 mb-8 transform transition-all duration-500 hover:scale-105">
            <div class="text-6xl mb-4 animate-bounce">🎉</div>
            <h2 class="text-4xl font-bold text-green-300 mb-4">Congratulations!</h2>
            <p class="text-xl mb-2">You escaped with <?php echo gmdate("i:s", $timeLeft); ?> remaining!</p>
            <p class="text-lg">Hints used: <?php echo $_SESSION['hints_used'] ?? 0; ?>/<?php echo MAX_HINTS; ?></p>
        </div>
    <?php else: ?>
        <div class="bg-red-900 border-2 border-red-400 rounded-lg p-8 mb-8 transform transition-all duration-500 hover:scale-105">
            <div class="text-6xl mb-4 animate-pulse">💀</div>
            <h2 class="text-4xl font-bold text-red-300 mb-4">Time's Up!</h2>
            <p class="text-xl mb-4">You didn't escape in time...</p>
            <p class="text-lg">
                <?php if (!$allPuzzlesSolved): ?>
                    Puzzles completed: <?php echo count($_SESSION['completed_puzzles'] ?? []); ?>/<?php echo TOTAL_PUZZLES; ?>
                <?php else: ?>
                    All puzzles solved, but too slowly!
                <?php endif; ?>
            </p>
        </div>
    <?php endif; ?>

    <div class="bg-gray-700 rounded-lg p-6 max-w-md mx-auto">
        <h3 class="text-xl font-semibold mb-4">Game Statistics</h3>
        <ul class="space-y-2 text-left">
            <li class="flex justify-between">
                <span>Total Time:</span>
                <span><?php echo gmdate("i:s", TOTAL_TIME); ?></span>
            </li>
            <li class="flex justify-between">
                <span>Time Remaining:</span>
                <span><?php echo gmdate("i:s", max(0, $timeLeft)); ?></span>
            </li>
            <li class="flex justify-between">
                <span>Hints Used:</span>
                <span><?php echo $_SESSION['hints_used'] ?? 0; ?>/<?php echo MAX_HINTS; ?></span>
            </li>
        </ul>
    </div>

    <div class="mt-8">
        <a href="index.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-full text-lg transition duration-300">
            <i class="fas fa-redo mr-2"></i> Play Again
        </a>
    </div>
</div>

<?php
require_once 'footer.php';
?>