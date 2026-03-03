<?php
declare(strict_types=1);

$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $number = $_POST['number'] ?? '';
    $digit = $_POST['digit'] ?? '';

    if (!ctype_digit($number) || !ctype_digit($digit) || strlen($digit) !== 1) {
        $error = "Будь ласка, введіть правильне число та одну цифру.";
    } else {
        $result = substr_count($number, $digit);
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Завдання 2</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Завдання №2</h1>

    <div class="form-container">
        <?php if ($error): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="alert alert-success">
                Цифра <strong><?= htmlspecialchars($digit) ?></strong> зустрічається <strong><?= $result ?></strong> разів у числі <strong><?= htmlspecialchars($number) ?></strong>.
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group">
                <label for="number">Число:</label>
                <input type="text" name="number" id="number" value="<?= $_POST['number'] ?? '442158755745' ?>" required>
            </div>
            <div class="input-group">
                <label for="digit">Цифра для пошуку:</label>
                <input type="text" name="digit" id="digit" maxlength="1" value="<?= $_POST['digit'] ?? '5' ?>" required>
            </div>
            <button type="submit" class="submit-btn">Порахувати</button>
        </form>

        <a href="../index.php" class="back-link">← Назад до списку</a>
    </div>
</body>
</html>
