<?php

// Init: $twig, $sessionManager, $requestHandler, $uiComponent
include __DIR__ . '/../../config/twig_init.php';

$navbar = $uiComponent->getNavbarData();

$article1 = file_get_contents(__DIR__ . '/../articles/article1.md');
$article2 = file_get_contents(__DIR__ . '/../articles/article2.md');
$article3 = file_get_contents(__DIR__ . '/../articles/article3.md');

$parsedown = new Parsedown();

// 'text()': Method of Parsedown that takes raw Markdown content and returns HTML representation
$articles = [
    $parsedown->text($article1),
    $parsedown->text($article2),
    $parsedown->text($article3),
];

$scrollspy_1 = [
    ['header' => 'Article 1', 'content' => $articles[0]],
    ['header' => 'Article 2', 'content' => $articles[1]],
    ['header' => 'Article 3', 'content' => $articles[2]],
];

$data = [
    'navbar' => $navbar,
    'scrollspy_1' => $scrollspy_1,
];

echo $twig->render('scrollspy.html.twig', $data);
