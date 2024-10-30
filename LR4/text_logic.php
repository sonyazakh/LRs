<?php
function remove_repeated_punctuation($text) {
    $text = preg_replace('/\.{3,}/u', '…', $text); // Много точек становится многоточием
    $text = preg_replace('/\.{2}/u', '.', $text); // Две точки становятся одной
    $text = preg_replace('/([!?])\1{2,}/u', '\$1\$1\$1', $text); // Восклицательные и вопросительные знаки до трех
    $text = preg_replace('/([,:\-;])\1+/u', '\$1', $text); // Удаление повторяющихся знаков препинания
    $text = preg_replace('/([(){}\[\]«»])\1+/u', '\$1', $text); // Удаление повторяющихся скобок
    return $text;
}

function generate_toc($text) {
    $pattern = '/<h([1-3])[^>]*>(.*?)<\/h[1-3]>/iu';
    preg_match_all($pattern, $text, $matches);

    $toc = '<h2>Оглавление</h2><ul>';
    $output_text = $text;

    foreach ($matches[0] as $key => $header) {
        $level = $matches[1][$key];
        $title = strip_tags($matches[2][$key]);
        $title = mb_substr($title, 0, 50) . (mb_strlen($title) > 50 ? '...' : '');

        $id = 'header-' . $key;
        $output_text = str_replace($header, "<h{$level} id=\"{$id}\">{$matches[2][$key]}</h{$level}>", $output_text);

        // Создание вложенного списка для оглавления
        $indentation = str_repeat('&nbsp;', ($level - 1) * 4); // Отступы по уровням
        $toc .= "<li style='margin-left: " . (($level - 1) * 20) . "px;'><a href='#{$id}'>{$indentation}" . htmlspecialchars($title) . "</a></li>";
    }

    $toc .= '</ul>';
    return [$toc, $output_text];
}

function filter_prohibited_words($text) {
    $prohibited_words = [
        'пух', 'рот', 'делать', 'ехать', 'около', 'для'
    ];

    foreach ($prohibited_words as $word) {
        $pattern = '/\b' . preg_quote($word, '/') . '\w*/iu'; // Учитываем производные слова
        $replacement = str_repeat('#', mb_strlen($word)); // Заменяем на ### (количество символов)
        $text = preg_replace($pattern, $replacement, $text);
    }

    return $text;
}
