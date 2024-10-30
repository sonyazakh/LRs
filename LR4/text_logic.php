<?php
function remove_repeated_punctuation($text) {
    $text = preg_replace('/\.{3,}/u', '…', $text);
    $text = preg_replace('/\.{2}/u', '.', $text);
    $text = preg_replace('/[!]+/u', '!!!', $text);
    $text = preg_replace('/[?]+/u', '???', $text);
    $text = preg_replace('/[,\-]+/u', ',', $text);
    $text = preg_replace('/[:\-]+/u', ':', $text);
    $text = preg_replace('/[;\-]+/u', ';', $text);

    $text = preg_replace('/\{+/', '{', $text);
    $text = preg_replace('/\}+/', '}', $text);

    $text = preg_replace('/\(+/', '(', $text);
    $text = preg_replace('/\)+/', ')', $text);

    $text = preg_replace('/\[++/', '[', $text);
    $text = preg_replace('/\]+/', ']', $text);

    $text = preg_replace('/«+/', '«', $text);
    $text = preg_replace('/»+/', '»', $text);
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


        $indentation = str_repeat('&nbsp;', ($level - 1) * 4);
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
        $pattern = '/\b' . preg_quote($word, '/') . '\w*/iu';
        $replacement = str_repeat('#', mb_strlen($word)); 
        $text = preg_replace($pattern, $replacement, $text);
    }

    return $text;
}
