<?php
declare(strict_types=1);

session_start();

class TreeNode
{
    public int $id;
    public string $name;
    public array $children = [];

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function addChild(TreeNode $child): void
    {
        $this->children[] = $child;
    }
}

class Tree
{
    private array $nodes = [];
    private array $roots = [];

    public function load(string $file): void
    {
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if (!$lines) {
            return;
        }

        foreach ($lines as $line) {
            [$id, $parentId, $name] = array_map('trim', explode('|', $line));

            $this->nodes[(int)$id] = [
                'node' => new TreeNode((int)$id, $name),
                'parent' => (int)$parentId
            ];
        }

        foreach ($this->nodes as $data) {
            $node = $data['node'];
            $parentId = $data['parent'];

            if ($parentId === 0) {
                $this->roots[] = $node;
            } else {
                $this->nodes[$parentId]['node']->addChild($node);
            }
        }
    }

    public function render(): string
    {
        $output = '';

        foreach ($this->roots as $root) {
            $output .= $this->renderNode($root, 0);
        }

        return $output;
    }

    private function renderNode(TreeNode $node, int $level): string
    {
        $result = str_repeat("\t", $level) . htmlspecialchars($node->name) . "<br>";

        foreach ($node->children as $child) {
            $result .= $this->renderNode($child, $level + 1);
        }

        return $result;
    }
}

class Apple
{
    public static array $apples = [];

    public string $color;
    public float $size = 1.0;

    protected string $status = 'tree';
    protected int $hoursOnGround = 0;
    protected bool $rotten = false;

    public function __construct(string $color)
    {
        $this->color = ucfirst(strtolower($color));
        self::$apples[] = $this;
    }

    public function fall_to_ground(): void
    {
        if ($this->status === 'tree') {
            $this->status = 'ground';
        }
    }

    public function eat(float $percent): void
    {
        if ($this->status !== 'ground') {
            return;
        }

        if ($this->rotten) {
            return;
        }

        if ($percent <= 0) {
            return;
        }

        $fraction = $percent / 100;

        if ($fraction > $this->size) {
            $fraction = $this->size;
        }

        $this->size -= $fraction;

        if ($this->size <= 0) {
            $this->remove();
        }
    }

    private function remove(): void
    {
        foreach (self::$apples as $key => $apple) {
            if ($apple === $this) {
                unset(self::$apples[$key]);
            }
        }

        self::$apples = array_values(self::$apples);
    }

    public static function lost_hour(): void
    {
        foreach (self::$apples as $apple) {
            if ($apple->status === 'ground') {
                $apple->hoursOnGround++;

                if ($apple->hoursOnGround >= 5) {
                    $apple->rotten = true;
                }
            }
        }
    }
}

$treeOutput = '';
$treeFile = __DIR__ . '/tree.txt';

if (file_exists($treeFile)) {
    $tree = new Tree();
    $tree->load($treeFile);
    $treeOutput = $tree->render();
}

$apple1 = new Apple('green');

$demo = '';
$demo .= "Color: " . $apple1->color . "<br>";
$demo .= "Static access: " . Apple::$apples[0]->color . "<br>";

$apple1->eat(50);
$demo .= "Size after eat(50): " . $apple1->size . "<br>";

$apple1->fall_to_ground();
$apple1->eat(25);
$demo .= "Size after fall + eat(25): " . $apple1->size . "<br>";

Apple::lost_hour();
$demo .= "Total apples in array: " . count(Apple::$apples) . "<br>";

?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Завдання 11</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<h1>Завдання №11</h1>

<div class="form-container wide">

    <h2>Дерево</h2>
    <div class="matrix-wrapper">
        <?= $treeOutput ?>
    </div>

    <h2>Apple Demo</h2>
    <div class="matrix-wrapper">
        <?= $demo ?>
    </div>

    <a href="../index.php" class="back-link">← Назад</a>

</div>

</body>
</html>