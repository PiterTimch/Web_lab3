<?php
declare(strict_types=1);

class Country
{
    private string $name;
    private int $population;
    private string $capital;

    public function __construct(string $name, int $population, string $capital)
    {
        if ($name === '' || $capital === '') {
            throw new InvalidArgumentException('Назва країни та столиці не можуть бути порожніми.');
        }

        if ($population <= 0) {
            throw new InvalidArgumentException('Населення повинно бути більше нуля.');
        }

        $this->name = $name;
        $this->population = $population;
        $this->capital = $capital;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPopulation(): int
    {
        return $this->population;
    }

    public function getCapital(): string
    {
        return $this->capital;
    }

    public function toTableRows(): array
    {
        return [
            'Country name' => $this->getName(),
            'Population'   => number_format($this->getPopulation(), 0, '.', ' '),
            'Capital city' => $this->getCapital(),
        ];
    }
}

$countries = [];

try {
    $countries[] = new Country('Germany', 83100000, 'Berlin');
    $countries[] = new Country('France', 67000000, 'Paris');
    $countries[] = new Country('Japan', 125000000, 'Tokyo');
    $countries[] = new Country('Canada', 39000000, 'Ottawa');
} catch (Throwable $e) {
    $error = $e->getMessage();
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

<div class="form-container wide">

    <div class="alert">
        <strong>Умова:</strong><br>
        Створити клас країни, в якому будуть поля: назва країни, населення 
        і назва столиці (англійські назви). Створити масив об'єктів, 
        вивести кожний з них у таблицю в три рядки по дві комірки 
        (ліворуч — ім'я елемента, праворуч — його значення).
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php foreach ($countries as $country): ?>
        <div class="matrix-wrapper">
            <table>
                <?php foreach ($country->toTableRows() as $label => $value): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($label) ?></strong></td>
                        <td><?= htmlspecialchars((string)$value) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endforeach; ?>

    <a href="../index.php" class="back-link">← Назад до списку</a>

</div>

</body>
</html>