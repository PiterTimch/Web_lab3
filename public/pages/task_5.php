<?php
declare(strict_types=1);

$maxDownloads = 10;
$counterFile = __DIR__ . '/../downloads/counter.txt';

if (!is_dir(__DIR__ . '/../downloads')) {
    mkdir(__DIR__ . '/../downloads', 0777, true);
}

if (!file_exists($counterFile)) {
    file_put_contents($counterFile, '0');
}

$counter = (int)file_get_contents($counterFile);
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($counter >= $maxDownloads) {
        $error = "Файл досяг ліміту завантажень.";
    }
    elseif (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        $error = "Будь ласка, оберіть файл.";
    }
    else {
        $tmpPath = $_FILES['file']['tmp_name'];
        $originalName = $_FILES['file']['name'];

        if (!is_uploaded_file($tmpPath)) {
            $error = "Помилка завантаження файлу.";
        }
        else {
            $counter++;
            file_put_contents($counterFile, (string)$counter);

            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($originalName) . '"');
            header('Content-Length: ' . filesize($tmpPath));

            readfile($tmpPath);
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Завантаження файлу</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

    <div class="form-container">

    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="input-group">
            <label>Оберіть файл:</label>
            <input type="file" name="file" required>
        </div>

        <button type="submit" class="submit-btn">
            Завантажити (залишилось <?= $maxDownloads - $counter ?>)
        </button>
    </form>

    <a href="../index.php" class="back-link">← Назад до списку</a>

    </div>
</body>
</html>
