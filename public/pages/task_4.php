<?php
declare(strict_types=1);

class Calc
{
    public function add(float $a, float $b): float
    {
        return $a + $b;
    }

    public function subtract(float $a, float $b): float
    {
        return $a - $b;
    }

    public function divide(float $a, float $b): float
    {
        if ($b == 0.0) {
            throw new InvalidArgumentException('Ділення на нуль неможливе.');
        }

        return $a / $b;
    }

    public function modulus(int $a, int $b): int
    {
        if ($b === 0) {
            throw new InvalidArgumentException('Операція mod з нулем неможлива.');
        }

        return $a % $b;
    }

    public function sqrt(float $a): float
    {
        if ($a < 0) {
            throw new InvalidArgumentException('Корінь з відʼємного числа неможливий.');
        }

        return sqrt($a);
    }

    public function power(float $a, float $b): float
    {
        return pow($a, $b);
    }
}

class CalcDispatcher
{
    private Calc $calc;
    private ?string $result = null;
    private ?string $error = null;

    public function __construct(Calc $calc)
    {
        $this->calc = $calc;
    }

    public function dispatch(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $a = $_POST['a'] ?? '';
        $b = $_POST['b'] ?? '';
        $operation = $_POST['operation'] ?? '';

        if (!is_numeric($a) || !is_numeric($b)) {
            $this->error = 'Введіть тільки числа.';
            return;
        }

        $a = (float)$a;
        $b = (float)$b;

        try {
            $this->result = match ($operation) {
                'add' => (string)$this->calc->add($a, $b),
                'sub' => (string)$this->calc->subtract($a, $b),
                'div' => (string)$this->calc->divide($a, $b),
                'mod' => (string)$this->calc->modulus((int)$a, (int)$b),
                'sqrt' => (string)$this->calc->sqrt($a),
                'pow' => (string)$this->calc->power($a, $b),
                default => 'Невідома операція'
            };
        } catch (Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function display(): string
    {
        $html = '';

        if ($this->error) {
            $html .= '<div class="alert alert-error">'
                . htmlspecialchars($this->error)
                . '</div>';
        }

        if ($this->result !== null) {
            $html .= '<div class="alert alert-success">
                        Результат: <strong>'
                        . htmlspecialchars($this->result)
                        . '</strong>
                      </div>';
        }

        return $html;
    }
}

$demo = new Calc();
$demoResults = [];

try {
    $demoResults[] = "5 + 3 = " . $demo->add(5, 3);
    $demoResults[] = "10 - 4 = " . $demo->subtract(10, 4);
    $demoResults[] = "8 / 2 = " . $demo->divide(8, 2);
    $demoResults[] = "7 mod 3 = " . $demo->modulus(7, 3);
    $demoResults[] = "sqrt(16) = " . $demo->sqrt(16);
    $demoResults[] = "2 ^ 3 = " . $demo->power(2, 3);
} catch (Throwable $e) {
    $demoResults[] = "Помилка: " . $e->getMessage();
}

$dispatcher = new CalcDispatcher(new Calc());
$dispatcher->dispatch();

?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Завдання 6-8</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<h1>Завдання №6–8</h1>

<div class="form-container wide">

    <div class="alert">
        6. Реалізувати клас Calc з усіма арифметичними методами.<br>
        7. Перевірити працездатність класу створивши обʼєкт і викликавши методи.<br>
        8. Створити CalcDispatcher. 
        Розрахунок — у display(), обробка — у dispatch().
        Звʼязок з Calc через агрегацію.
    </div>

    <div class="matrix-wrapper">
        <table>
            <tr>
                <td><strong>Демонстрація методів</strong></td>
            </tr>
            <?php foreach ($demoResults as $line): ?>
                <tr>
                    <td><?= htmlspecialchars($line) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <form method="POST">

        <div class="input-group">
            <label>Число A:</label>
            <input type="text" name="a" required>
        </div>

        <div class="input-group">
            <label>Число B:</label>
            <input type="text" name="b" required>
        </div>

        <div class="input-group">
            <label>Операція:</label>
            <select name="operation" required>
                <option value="add">Додавання</option>
                <option value="sub">Віднімання</option>
                <option value="div">Ділення</option>
                <option value="mod">Mod</option>
                <option value="sqrt">Корінь (A)</option>
                <option value="pow">Степінь (A^B)</option>
            </select>
        </div>

        <button type="submit" class="submit-btn">Обчислити</button>
    </form>

    <?= $dispatcher->display(); ?>

    <a href="../index.php" class="back-link">← Назад до списку</a>

</div>

</body>
</html>