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

// Determine the current page and total number of pages
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$articles_per_page = 1;
$total_articles = count($articles);
$total_pages = ceil($total_articles / $articles_per_page);

// Get the current article content for the page
$start_index = ($current_page - 1) * $articles_per_page;
$article_content = $articles[$start_index] ?? "No content available.";

$data = [
    'navbar' => $navbar,
    'articles' => $articles,
    'current_page' => $current_page,
    'total_pages' => $total_pages,
    'article_content' => $article_content,
];

echo $twig->render('pagination.html.twig', $data);

?>