<?php
require_once 'config.php';

if (isset($_POST['reset_game'])) { // Completely clears session when "reset" button is pressed
    session_unset();
    session_destroy();
    
    session_start();
    
    header("Location: index.php");
    exit;
}

require_once 'header.php';
?>

<div class="max-w-3xl mx-auto text-center py-12">
    <h2 class="text-4xl font-bold mb-6 text-red-400">Welcome to the Escape Room Challenge!</h2>
    
    <div class="bg-gray-700 p-8 rounded-lg shadow-lg mb-8">
        <p class="text-lg mb-4">You're locked in a virtual room with <?php echo TOTAL_PUZZLES; ?> puzzles to solve.</p>
        <p class="text-lg mb-6">You have <span class="text-red-400 font-bold"><?php echo gmdate("i:s", TOTAL_TIME); ?></span> to escape!</p>
        
        <ul class="text-left list-disc pl-6 mb-8 space-y-2">
            <li>Solve all puzzles before time runs out</li>
            <li>You have <?php echo MAX_HINTS; ?> hints available</li>
            <li>Progress is saved automatically</li>
        </ul>
        
        <div class="flex justify-center space-x-4">
            <form action="puzzle1.php" method="post">
                <input type="hidden" name="start_game" value="1">
                <button type="submit" 
                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-full text-lg
                               transition duration-300 transform hover:scale-105">
                    <i class="fas fa-door-open mr-2"></i> Start Challenge
                </button>
            </form>
            
            <?php if (isset($_SESSION['game_started'])): ?>
            <form method="post">
                <button type="submit" 
                        name="reset_game"
                        class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3 px-8 rounded-full text-lg
                               transition duration-300 transform hover:scale-105">
                    <i class="fas fa-redo-alt mr-2"></i> Reset Game
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="bg-gray-700 p-6 rounded-lg">
        <h3 class="text-xl font-semibold mb-3">How to Play:</h3>
        <ol class="text-left list-decimal pl-6 space-y-2">
            <li>Read each puzzle carefully</li>
            <li>Type your answer in the provided field</li>
            <li>Submit your answer to proceed</li>
            <li>Use hints wisely - they affect your final score!</li>
        </ol>
    </div>
</div>

<?php
require_once 'footer.php';
?>