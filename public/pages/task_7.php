<?php
declare(strict_types=1);

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

        $waitingChildren = [];

        foreach ($lines as $line) {
            [$id, $parentId, $name] = array_map('trim', explode('|', $line));

            $id = (int)$id;
            $parentId = (int)$parentId;

            $node = new TreeNode($id, $name);
            $this->nodes[$id] = $node;

            if ($parentId === 0) {
                $this->roots[] = $node;
            } else {
                if (isset($this->nodes[$parentId])) {
                    $this->nodes[$parentId]->children[] = $node;
                } else {
                    $waitingChildren[$parentId][] = $node;
                }
            }

            if (isset($waitingChildren[$id])) {
                foreach ($waitingChildren[$id] as $child) {
                    $node->children[] = $child;
                }
                unset($waitingChildren[$id]);
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
        $line = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level)
              . htmlspecialchars($node->name)
              . "<br>";

        foreach ($node->children as $child) {
            $line .= $this->renderNode($child, $level + 1);
        }

        return $line;
    }
}

$treeOutput = '';
$treeFile = __DIR__ . '/tree.txt';

if (file_exists($treeFile)) {
    $tree = new Tree();
    $tree->load($treeFile);
    $treeOutput = $tree->render();
}
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

    <div class="alert">
        Є текстовий файл з описом дерева (node_id | parent_id | node_name).<br>
        Необхідно відобразити його як дерево каталогів без зайвих ітерацій.
    </div>

    <h2>Структура дерева</h2>

    <div class="matrix-wrapper">
        <?= $treeOutput ?>
    </div>

    <a href="../index.php" class="back-link">← Назад</a>

</div>

</body>
</html>