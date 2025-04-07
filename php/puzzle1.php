<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['start_game'])) {
    $_SESSION['game_started'] = time();
    $_SESSION['current_puzzle'] = 1;

    header("Location: puzzle1.php");
    exit;
}

if (!isset($_SESSION['game_started'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_SESSION['sudoku_grid'])) {
    $_SESSION['sudoku_solution'] = [
        [9, 8, 5, 4, 2, 1, 7, 6, 3],
        [1, 7, 6, 5, 3, 8, 2, 9, 4],
        [3, 4, 2, 7, 6, 9, 5, 1, 8],
        [4, 5, 8, 2, 1, 9, 6, 3, 7],
        [7, 9, 1, 6, 3, 4, 8, 5, 2],
        [6, 2, 3, 8, 9, 7, 1, 4, 5],
        [5, 6, 7, 3, 4, 2, 9, 8, 1],
        [2, 1, 4, 9, 8, 5, 3, 7, 6],
        [8, 3, 9, 1, 7, 6, 4, 2, 5]
    ];
    
    $_SESSION['sudoku_grid'] = [
        [9, 8, 5, 4, 0, 1, 0, 0, 0],
        [1, 0, 6, 0, 3, 0, 0, 0, 0],
        [3, 0, 0, 0, 0, 0, 5, 0, 0],
        [4, 5, 8, 0, 0, 9, 0, 0, 7],
        [0, 9, 1, 0, 3, 4, 0, 0, 0],
        [6, 0, 3, 0, 0, 0, 1, 0, 0],
        [0, 0, 7, 3, 0, 0, 0, 8, 1],
        [2, 1, 0, 0, 8, 5, 0, 0, 6],
        [8, 0, 0, 0, 7, 0, 0, 0, 5]
    ];
    
    $_SESSION['sudoku_hints_revealed'] = [];
    
    $_SESSION['user_progress'] = array_map(function($row) {
        return array_map(function($cell) {
            return $cell === 0 ? 0 : $cell;
        }, $row);
    }, $_SESSION['sudoku_grid']);
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['use_hint']) && $_SESSION['hints_used'] < MAX_HINTS) {
    $user_progress = [];
    for ($i = 0; $i < 9; $i++) {
        $row = [];
        for ($j = 0; $j < 9; $j++) {
            $cell_key = "cell_{$i}_{$j}";
            if (isset($_POST[$cell_key]) && !empty($_POST[$cell_key])) {
                $row[] = (int)$_POST[$cell_key];
            } else {
                if ($_SESSION['sudoku_grid'][$i][$j] !== 0) {
                    $row[] = $_SESSION['sudoku_grid'][$i][$j];
                } else {
                    $row[] = 0; 
                }
            }
        }
        $user_progress[] = $row;
    }
    
    $_SESSION['user_progress'] = $user_progress;
    
    $_SESSION['hints_used']++;
    $hintUsed = true;
    
    $empty_cells = [];
    for ($i = 0; $i < 9; $i++) {
        for ($j = 0; $j < 9; $j++) {
            $key = "{$i}_{$j}";
            if ($_SESSION['sudoku_grid'][$i][$j] === 0 && 
                !in_array($key, $_SESSION['sudoku_hints_revealed']) && 
                $user_progress[$i][$j] === 0) {
                $empty_cells[] = $key;
            }
        }
    }
    
    if (count($empty_cells) > 0) {
        $random_index = array_rand($empty_cells);
        $cell_to_reveal = $empty_cells[$random_index];
        list($row, $col) = explode('_', $cell_to_reveal);
        
        $_SESSION['sudoku_grid'][$row][$col] = $_SESSION['sudoku_solution'][$row][$col];
        
        $_SESSION['user_progress'][$row][$col] = $_SESSION['sudoku_solution'][$row][$col];
        
        $_SESSION['sudoku_hints_revealed'][] = $cell_to_reveal;
    }
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_answer'])) {
    $submitted_grid = [];
    $is_valid = true;
    
    for ($i = 0; $i < 9; $i++) {
        $row = [];
        for ($j = 0; $j < 9; $j++) {
            $cell_value = isset($_POST["cell_{$i}_{$j}"]) && !empty($_POST["cell_{$i}_{$j}"]) 
                        ? (int)$_POST["cell_{$i}_{$j}"] 
                        : 0;
            $row[] = $cell_value;
            
            if ($cell_value !== $_SESSION['sudoku_solution'][$i][$j] && $cell_value !== 0) {
                $is_valid = false;
            }
        }
        $submitted_grid[] = $row;
    }
    
    $all_filled = true;
    foreach ($submitted_grid as $row) {
        if (in_array(0, $row)) {
            $all_filled = false;
            break;
        }
    }
    
    if ($is_valid && $all_filled) {
        $_SESSION['completed_puzzles'][] = 1;
        $_SESSION['current_puzzle'] = 2;
        header('Location: puzzle2.php');
        exit;
    } elseif (!$is_valid) {
        $error = 'Your solution is not correct. Check again!';
    } elseif (!$all_filled) {
        $error = 'Please fill in all cells before submitting.';
    }
    
    $_SESSION['user_progress'] = $submitted_grid;
}
else {
    $hintUsed = false;
}

require_once 'header.php';
?>
<style>
    <?php include '../css/puzzle1.css'?>
</style>

<div class="max-w-3xl mx-auto py-8">
    <div class="bg-gray-700 p-6 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold mb-6 text-blue-400 border-b border-blue-400 pb-2">
            <i class="fas fa-puzzle-piece mr-2"></i> Puzzle 1: Sudoku Challenge
        </h2>
        
        <div class="mb-6">
            <p class="text-lg mb-4">Solve the Sudoku puzzle to unlock the next challenge. Fill in the grid so that every row, column, and 3×3 box contains the digits 1 through 9 without repetition.</p>
            
            <?php if ($error): ?>
                <div class="bg-red-500 text-white p-3 rounded mb-4"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="post" class="space-y-4">
                <div class="flex justify-center my-6">
                    <table class="sudoku-grid">
                        <?php for ($i = 0; $i < 9; $i++): ?>
                            <tr class="sudoku-row">
                                <?php for ($j = 0; $j < 9; $j++): ?>
                                    <?php 
                                        $display_value = isset($_SESSION['user_progress'][$i][$j]) && 
                                                      $_SESSION['user_progress'][$i][$j] !== 0 
                                                      ? $_SESSION['user_progress'][$i][$j] 
                                                      : $_SESSION['sudoku_grid'][$i][$j];
                                        
                                        $is_original = $_SESSION['sudoku_grid'][$i][$j] !== 0 && 
                                                      !in_array("{$i}_{$j}", $_SESSION['sudoku_hints_revealed'] ?? []);
                                        
                                        $is_hint = in_array("{$i}_{$j}", $_SESSION['sudoku_hints_revealed'] ?? []);
                                        
                                        $cell_class = "sudoku-cell";
                                        if ($is_original) $cell_class .= " original";
                                        if ($is_hint) $cell_class .= " hint";
                                    ?>
                                    <td>
                                        <input 
                                            type="text" 
                                            name="cell_<?php echo $i; ?>_<?php echo $j; ?>" 
                                            value="<?php echo $display_value !== 0 ? $display_value : ''; ?>" 
                                            maxlength="1"
                                            pattern="[1-9]"
                                            inputmode="numeric"
                                            class="<?php echo $cell_class; ?>"
                                            <?php echo ($is_original || $is_hint) ? 'readonly' : ''; ?>
                                            oninput="this.value=this.value.replace(/[^1-9]/g,'')"
                                        >
                                    </td>
                                <?php endfor; ?>
                            </tr>
                        <?php endfor; ?>
                    </table>
                </div>
                
                <div class="flex justify-between">
                    <button type="submit" name="submit_answer"
                            class="bg-green-600 hover:bg-green-700 px-6 py-2 rounded font-bold text-white">
                        <i class="fas fa-check mr-2"></i> Submit Solution
                    </button>
                    
                    <?php if ($_SESSION['hints_used'] < MAX_HINTS): ?>
                        <button type="submit" name="use_hint"
                                class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded font-bold text-white">
                            <i class="fas fa-lightbulb mr-2"></i> Use Hint (<?php echo MAX_HINTS - $_SESSION['hints_used']; ?> left)
                        </button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        
        <?php if ($hintUsed): ?>
            <div class="bg-blue-900 p-4 rounded-lg border border-blue-400 mt-4 animate-pulse">
                <h3 class="font-bold text-blue-300 mb-2"><i class="fas fa-info-circle mr-2"></i>Hint:</h3>
                <p>A number has been revealed on the grid! Look for the highlighted cell in blue.</p>
            </div>
        <?php endif; ?>
    
        <div class="mt-6 bg-gray-800 p-4 rounded-lg">
            <h3 class="text-lg font-semibold mb-3 text-blue-400">Sudoku Rules:</h3>
            <ul class="list-disc pl-4 space-y-2 text-sm">
                <li>Fill in the grid so that every row contains the numbers 1-9</li>
                <li>Every column must contain the numbers 1-9</li>
                <li>Each of the nine 3×3 boxes must contain the numbers 1-9</li>
                <li>No number can appear more than once in any row, column, or 3×3 box</li>
            </ul>
        </div>
    </div>
</div>
<?php
require_once 'footer.php';
?>
