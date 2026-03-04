<?php
declare(strict_types=1);

class User
{
    private string $lastName;
    private string $firstName;
    private int $age;
    private string $email;

    public function fill(string $lastName, string $firstName, int $age, string $email): void
    {
        $this->setLastName($lastName);
        $this->setFirstName($firstName);
        $this->setAge($age);
        $this->setEmail($email);
    }

    public function setLastName(string $lastName): void
    {
        $lastName = trim($lastName);

        if ($lastName === '') {
            throw new InvalidArgumentException('Прізвище не може бути порожнім.');
        }

        $this->lastName = $lastName;
    }

    public function setFirstName(string $firstName): void
    {
        $firstName = trim($firstName);

        if ($firstName === '') {
            throw new InvalidArgumentException('Імʼя не може бути порожнім.');
        }

        $this->firstName = $firstName;
    }

    public function setAge(int $age): void
    {
        if ($age <= 0 || $age > 120) {
            throw new InvalidArgumentException('Вік повинен бути в межах 1–120.');
        }

        $this->age = $age;
    }

    public function setEmail(string $email): void
    {
        $email = trim($email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Некоректний формат e-mail.');
        }

        $this->email = $email;
    }

    public function render(): string
    {
        return '
        <div class="matrix-wrapper">
            <table>
                <tr>
                    <td><strong>Прізвище</strong></td>
                    <td>' . htmlspecialchars($this->lastName) . '</td>
                </tr>
                <tr>
                    <td><strong>Імʼя</strong></td>
                    <td>' . htmlspecialchars($this->firstName) . '</td>
                </tr>
                <tr>
                    <td><strong>Вік</strong></td>
                    <td>' . $this->age . '</td>
                </tr>
                <tr>
                    <td><strong>E-mail</strong></td>
                    <td>' . htmlspecialchars($this->email) . '</td>
                </tr>
            </table>
        </div>';
    }
}

$error = null;
$user = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $lastName  = trim($_POST['last_name'] ?? '');
    $firstName = trim($_POST['first_name'] ?? '');
    $ageInput  = trim($_POST['age'] ?? '');
    $email     = trim($_POST['email'] ?? '');

    if ($lastName === '' || $firstName === '' || $ageInput === '' || $email === '') {
        $error = 'Усі поля повинні бути заповнені.';
    } elseif (!ctype_digit($ageInput)) {
        $error = 'Вік повинен бути числом.';
    } else {
        try {
            $user = new User();
            $user->fill($lastName, $firstName, (int)$ageInput, $email);
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
    <title>Завдання 3-5</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<h1>Завдання №3–5</h1>

<div class="form-container wide">

    <div class="alert">
        <strong>Умова:</strong><br>
        3. Створити клас користувача з полями: прізвище, імʼя, вік, e-mail.<br>
        4. У формі користувач вводить дані. Після натискання кнопки створюється обʼєкт 
        користувача, метод класу заповнює поля, інший метод виводить дані.<br>
        5. Передбачити перевірку, що всі поля не порожні.
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($user): ?>
        <div class="alert alert-success">
            Дані користувача успішно створені.
        </div>
        <?= $user->render(); ?>
    <?php endif; ?>

    <form method="POST">
        <div class="input-group">
            <label for="last_name">Прізвище:</label>
            <input type="text" name="last_name" id="last_name"
                   value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>">
        </div>

        <div class="input-group">
            <label for="first_name">Імʼя:</label>
            <input type="text" name="first_name" id="first_name"
                   value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>">
        </div>

        <div class="input-group">
            <label for="age">Вік:</label>
            <input type="number" name="age" id="age"
                   value="<?= htmlspecialchars($_POST['age'] ?? '') ?>" min="1" max="120">
        </div>

        <div class="input-group">
            <label for="email">E-mail:</label>
            <input type="text" name="email" id="email"
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>

        <button type="submit" class="submit-btn">ГОТОВО</button>
    </form>

    <a href="../index.php" class="back-link">← Назад до списку</a>

</div>

</body>
</html>