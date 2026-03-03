<?php
declare(strict_types=1);

session_start();

class PlayerAuth
{
    public function handle(): void
    {
        if (!isset($_SESSION['player1'], $_SESSION['player2'])) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $p1 = trim($_POST['player1'] ?? '');
                $p2 = trim($_POST['player2'] ?? '');

                if ($p1 !== '' && $p2 !== '') {
                    $_SESSION['player1'] = $p1;
                    $_SESSION['player2'] = $p2;
                }
            }
        }
    }

    public function render(): void
    {
        echo '
        <div class="form-container">
            <form method="POST">
                <div class="input-group">
                    <label>Гравець 1 (X):</label>
                    <input type="text" name="player1" required>
                </div>
                <div class="input-group">
                    <label>Гравець 2 (O):</label>
                    <input type="text" name="player2" required>
                </div>
                <button class="submit-btn">Почати гру</button>
            </form>
        </div>';
    }
}

class GameEngine
{
    public function __construct()
    {
        if (!isset($_SESSION['board'])) {
            $this->reset();
        }
    }

    public function reset(): void
    {
        $_SESSION['board'] = array_fill(0, 9, '');
        $_SESSION['turn'] = 'X';
        $_SESSION['winner'] = null;
    }

    public function move(int $index): void
    {
        if ($_SESSION['winner']) {
            return;
        }

        if ($_SESSION['board'][$index] !== '') {
            $_SESSION['message'] = 'Клітинка вже зайнята!';
            return;
        }

        $_SESSION['board'][$index] = $_SESSION['turn'];

        if ($this->checkWin($_SESSION['turn'])) {
            $_SESSION['winner'] = $_SESSION['turn'];
            return;
        }

        if (!in_array('', $_SESSION['board'], true)) {
            $_SESSION['winner'] = 'draw';
            return;
        }

        $_SESSION['turn'] = $_SESSION['turn'] === 'X' ? 'O' : 'X';
    }

    private function checkWin(string $symbol): bool
    {
        $b = $_SESSION['board'];

        $wins = [
            [0,1,2],[3,4,5],[6,7,8],
            [0,3,6],[1,4,7],[2,5,8],
            [0,4,8],[2,4,6]
        ];

        foreach ($wins as $line) {
            if ($b[$line[0]] === $symbol &&
                $b[$line[1]] === $symbol &&
                $b[$line[2]] === $symbol) {
                return true;
            }
        }

        return false;
    }
}

class GameBoard
{
    public function render(): void
    {
        echo '<div class="matrix-wrapper"><table style="min-width:auto;">';

        for ($i = 0; $i < 9; $i++) {
            if ($i % 3 === 0) {
                echo '<tr>';
            }

            $value = $_SESSION['board'][$i];

            echo '<td>
                <form method="POST" style="margin:0;">
                    <input type="hidden" name="move" value="'.$i.'">
                    <button class="submit-btn" style="width:60px;height:60px;">
                        '.htmlspecialchars($value ?: ' ').'
                    </button>
                </form>
            </td>';

            if ($i % 3 === 2) {
                echo '</tr>';
            }
        }

        echo '</table></div>';
    }
}

$auth = new PlayerAuth();
$auth->handle();

if (isset($_POST['reset'])) {
    session_destroy();
    header("Location: task_6.php");
    exit;
}

if (isset($_SESSION['player1'], $_SESSION['player2'])) {
    $game = new GameEngine();

    if (isset($_POST['move'])) {
        $game->move((int)$_POST['move']);
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Завдання 10</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<h1>Хрестики-Нулики</h1>

<div class="form-container wide">

<?php if (!isset($_SESSION['player1'])): ?>

    <div class="alert">
        Введіть імена двох гравців.
    </div>

    <?php $auth->render(); ?>

<?php else: ?>

    <div class="alert alert-success">
        Хід: <?= htmlspecialchars($_SESSION['turn']) ?>
        <br>
        <?php if (isset($_SESSION['message'])):
            echo htmlspecialchars($_SESSION['message']);
            unset($_SESSION['message']);
        endif; ?>
    </div>

    <?php
        $board = new GameBoard();
        $board->render();
    ?>

    <?php if ($_SESSION['winner']): ?>
        <div class="alert alert-success">
            <?php
            if ($_SESSION['winner'] === 'draw') {
                echo 'Нічия!';
            } else {
                echo 'Переміг: ' . htmlspecialchars($_SESSION['winner']);
            }
            ?>
        </div>

        <form method="POST">
            <button name="reset" class="submit-btn">
                Почати заново
            </button>
        </form>

    <?php endif; ?>

<?php endif; ?>

<a href="../index.php" class="back-link">← Назад до списку</a>

</div>

</body>
</html>