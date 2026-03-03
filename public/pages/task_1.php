<?php
declare(strict_types=1);

class MultiplicationTable
{
    private int $number;
    private array $results = [];

    public function __construct(int $number)
    {
        if ($number <= 0) {
            throw new InvalidArgumentException('Число повинно бути більше нуля.');
        }

        $this->number = $number;
    }

    public function calculate(): void
    {
        $this->results = [];

        for ($i = 1; $i <= 10; $i++) {
            $this->results[] = [
                'multiplier' => $i,
                'product' => $this->number * $i
            ];
        }
    }

    public function render(): string
    {
        if (empty($this->results)) {
            $this->calculate();
        }

        $html = '<div class="matrix-wrapper">';
        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<td colspan="3"><strong>Таблиця множення для числа '
            . htmlspecialchars((string)$this->number)
            . '</strong></td>';
        $html .= '</tr>';

        foreach ($this->results as $row) {
            $html .= '<tr>';
            $html .= '<td>' . $this->number . '</td>';
            $html .= '<td>× ' . $row['multiplier'] . '</td>';
            $html .= '<td>= ' . $row['product'] . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';
        $html .= '</div>';

        return $html;
    }
}

$error = null;
$tables = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = trim($_POST['number'] ?? '');

    if ($input === '' || !ctype_digit($input)) {
        $error = 'Будь ласка, введіть додатнє ціле число.';
    } else {
        try {
            $number = (int)$input;

            $tables[] = new MultiplicationTable($number);
            $tables[] = new MultiplicationTable($number + 1);
            $tables[] = new MultiplicationTable($number + 2);

        } catch (Throwable $e) {
            $error = $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Завдання 1</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<h1>Завдання №1</h1>

<div class="form-container wide">

    <div class="alert">
        <strong>Умова:</strong><br>
        Створити клас для виведення таблиці множення для вказаного числа 
        (передавати в конструкторі). Створити окремий метод для обчислення. 
        Далі створити кілька об'єктів даного класу для демонстрації працездатності класу. 
        Висновок оформити у вигляді таблиці.
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="input-group">
            <label for="number">Введіть число:</label>
            <input type="number" name="number" id="number"
                   value="<?= htmlspecialchars($_POST['number'] ?? '5') ?>"
                   min="1" required>
        </div>
        <button type="submit" class="submit-btn">Побудувати таблиці</button>
    </form>

    <?php
    if (!empty($tables)) {
        foreach ($tables as $table) {
            echo $table->render();
        }
    }
    ?>

    <a href="../index.php" class="back-link">← Назад до списку</a>

</div>

</body>
</html>