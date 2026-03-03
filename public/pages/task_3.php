<?php
declare(strict_types=1);

$result = null;
$error = null;

$currencies = ['UAH', 'USD', 'EUR', 'GBP'];

$rates = [
    'UAH' => 1.0,
    'USD' => 43.5,
    'EUR' => 50.7,
    'GBP' => 58.2
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'] ?? '';
    $from = $_POST['from'] ?? '';
    $to = $_POST['to'] ?? '';

    if (!is_numeric($amount) || $amount <= 0 || !in_array($from, $currencies) || !in_array($to, $currencies)) {
        $error = "Будь ласка, введіть коректну суму та валюти.";
    } else {
        $amount = (float)$amount;

        if ($from === $to) {
            $result = $amount;
        } else {
            $uah = $amount * $rates[$from];
            $result = $uah / $rates[$to];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Завдання 3</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<h1>Завдання №3</h1>

    <div class="form-container">

        <?php if ($error): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars((string)$amount) ?> <?= htmlspecialchars($from) ?> =
                <strong><?= htmlspecialchars(number_format($result, 2)) ?> <?= htmlspecialchars($to) ?></strong>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="input-group">
                <label for="amount">Сума:</label>
                <input type="number" name="amount" id="amount" step="0.01"
                    value="<?= $_POST['amount'] ?? '100' ?>" required>
            </div>

            <div class="input-group">
                <label for="from">Валюта (з):</label>
                <select name="from" id="from">
                    <?php foreach ($currencies as $c): ?>
                        <option value="<?= $c ?>" <?= ($_POST['from'] ?? '') === $c ? 'selected' : '' ?>>
                            <?= $c ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="input-group">
                <label for="to">Валюта (в):</label>
                <select name="to" id="to">
                    <?php foreach ($currencies as $c): ?>
                        <option value="<?= $c ?>" <?= ($_POST['to'] ?? '') === $c ? 'selected' : '' ?>>
                            <?= $c ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="submit-btn">Конвертувати</button>
        </form>

        <a href="../index.php" class="back-link">← Назад до списку</a>

    </div>
</body>
</html>
