<?php
declare(strict_types=1);

$result = null;
$error = null;

$uploadDir = __DIR__ . '/../uploads/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filePath = $_FILES['r1file']['tmp_name'] ?? null;
    $originalName = $_FILES['r1file']['name'] ?? 'R1.txt';

    if (!$filePath || !is_uploaded_file($filePath)) {
        $error = "Будь ласка, завантажте файл R1.";
    } else {
        $numbers = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $numbers = array_map('floatval', $numbers);
        $r2 = [];

        for ($i = 0; $i < count($numbers); $i += 10) {
            $slice = array_slice($numbers, $i, 10);
            if (!$slice) continue;
            $r2[] = max($slice);
            $r2[] = min($slice);
        }

        $r2FileName = pathinfo($originalName, PATHINFO_FILENAME) . '_R2.txt';
        $r2Path = $uploadDir . $r2FileName;

        file_put_contents($r2Path, implode(PHP_EOL, $r2));

        $result = '../uploads/' . $r2FileName;
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Завдання 4</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<h1>Завдання №4</h1>

<div class="form-container">

    <?php if ($error): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($result): ?>
        <div class="alert alert-success">
            Файл R2 створено:
            <a href="<?= htmlspecialchars($result) ?>" download>
                <strong><?= basename($result) ?></strong>
            </a>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="input-group">
            <label for="r1file">Завантажте файл R1:</label>
            <input type="file" name="r1file" id="r1file" accept=".txt" required>
        </div>
        <button type="submit" class="submit-btn">Обробити</button>
    </form>

    <a href="../index.php" class="back-link">← Назад до списку</a>

</div>
</body>
</html>
