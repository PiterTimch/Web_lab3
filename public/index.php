<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лабораторна робота №3</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>Лабораторна робота №3</h1>
    <h2>Тимчук Петро: ІПЗ-22, варіант 11</h2>

    <div class="button-container">
        <?php for ($i = 1; $i <= 7; $i++): ?>
            <a href="pages/task_<?php echo $i; ?>.php" class="task-btn">
                Завдання <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </div>

</body>
</html>