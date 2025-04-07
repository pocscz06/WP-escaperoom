<?php
require_once 'config.php';

// Validate puzzle access
if (!in_array(2, $_SESSION['completed_puzzles'])) {
    header('Location: index.php');
    exit;
}

// Puzzle answer validation
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_answer'])) {
    $answer = strtolower(trim($_POST['answer']));
    if ($answer === 'triangle' || $answer === 'triangles') {
        $_SESSION['completed_puzzles'][] = 3;
        header('Location: result.php');
        exit;
    } else {
        $error = 'Incorrect answer! Try again.';
    }
}

// Hint system
$hintUsed = false;
if (isset($_POST['use_hint']) && $_SESSION['hints_used'] < MAX_HINTS) {
    $_SESSION['hints_used']++;
    $hintUsed = true;
}

require_once 'header.php';
?>

<div class="max-w-3xl mx-auto py-8">
    <div class="bg-gray-700 p-8 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold mb-6 text-green-400 border-b border-green-400 pb-2">
            <i class="fas fa-shapes mr-2"></i> Puzzle 3: Pattern Recognition
        </h2>
        
        <div class="mb-8">
            <p class="text-lg mb-4">What shape results from this sequence?</p>
            <div class="bg-black p-6 rounded text-center text-2xl font-mono mb-6">
                <div class="flex justify-center space-x-8 mb-4">
                    <div class="w-16 h-16 border-b-4 border-l-4 border-r-4 border-white transform rotate-45"></div>
                    <div class="w-16 h-16 border-t-4 border-r-4 border-l-4 border-white transform rotate-45"></div>
                    <div class="w-16 h-16 border-t-4 border-b-4 border-l-4 border-white transform rotate-45"></div>
                </div>
                <p>?</p>
            </div>
            
            <?php if ($error): ?>
                <div class="bg-red-500 text-white p-3 rounded mb-4"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="post" class="space-y-4">
                <div>
                    <label for="answer" class="block text-sm font-medium mb-1">Your Answer:</label>
                    <input type="text" id="answer" name="answer" 
                           class="w-full px-4 py-2 rounded bg-gray-800 border border-gray-600 focus:border-green-400 focus:outline-none"
                           required>
                </div>
                
                <div class="flex justify-between">
                    <button type="submit" name="submit_answer"
                            class="bg-green-600 hover:bg-green-700 px-6 py-2 rounded font-bold">
                        <i class="fas fa-check mr-2"></i> Submit Answer
                    </button>
                    
                    <?php if ($_SESSION['hints_used'] < MAX_HINTS): ?>
                        <button type="submit" name="use_hint"
                                class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded font-bold">
                            <i class="fas fa-lightbulb mr-2"></i> Use Hint (<?php echo MAX_HINTS - $_SESSION['hints_used']; ?> left)
                        </button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        
        <?php if ($hintUsed): ?>
            <div class="bg-blue-900 p-4 rounded-lg border border-blue-400 mt-4 animate-pulse">
                <h3 class="font-bold text-blue-300 mb-2"><i class="fas fa-info-circle mr-2"></i>Hint:</h3>
                <p>Each shape represents a triangle with one side missing. The pattern shows which side is missing in each case.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
require_once 'footer.php';
?>