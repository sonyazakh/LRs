<?php
require 'header.php';

$preset_files = [
    1 => "presets/preset1.txt",
    2 => "presets/preset2.txt",
    3 => "presets/preset3.txt"
];

$preset = isset($_GET['preset']) ? (int)$_GET['preset'] : null;

$default_text = "";
if (isset($preset_files[$preset]) && file_exists($preset_files[$preset])) {
    $default_text = file_get_contents($preset_files[$preset]);
}

$submitted_text = isset($_POST['html_content']) ? $_POST['html_content'] : $default_text;
$display_text = $submitted_text;

require "text_logic.php";

$toc = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $display_text = remove_repeated_punctuation($display_text);
    list($toc, $output_text) = generate_toc($display_text);
    $display_text = filter_prohibited_words($output_text);
}

function is_checked($task_name) {
    return isset($_POST[$task_name]) ? 'checked' : '';
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактор HTML</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../LR4/css/style.css">
</head>
<body>
<div class="container mt-4">
    <h1>Редактор HTML</h1>

    <div class="mb-3">
        <a href="?preset=1" class="btn btn-secondary">Пример 1 (Википедия)</a>
        <a href="?preset=2" class="btn btn-secondary">Пример 2 (Gazeta.ru)</a>
        <a href="?preset=3" class="btn btn-secondary">Пример 3 (Винни-Пух)</a>
    </div>

    <form method="POST">
        <div class="mb-3">
            <label for="html_content" class="form-label">HTML-код:</label>
            <textarea class="form-control" id="html_content" name="html_content" rows="10"><?php echo htmlspecialchars($submitted_text); ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Отправить</button>
    </form>

    <?php if (!empty($toc)): ?>
        <h2 class="mt-4">Оглавление:</h2>
        <div class="border p-3">
            <?php echo $toc; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($display_text)): ?>
        <h2 class="mt-4">Результат:</h2>
        <div class="border p-3">
            <?php echo $display_text; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
