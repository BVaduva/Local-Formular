<?php

// Init: $twig, $sessionManager, $requestHandler, $uiComponent
include __DIR__ . '/../../config/twig_init.php';

$navbar = $uiComponent->getNavbarData();

$content = file_get_contents(__DIR__ . '/../articles/article_tooltip.md');

// Parse the content for tooltips first
$parsedContentWithTooltips = $uiComponent->parseTooltips($content, "success");

// Now, convert the content with tooltips to HTML using Parsedown
$parsedown = new Parsedown();
$parsedContent = $parsedown->text($parsedContentWithTooltips);

$data = [
    'navbar' => $navbar,
    'article_content' => $parsedContent,
];

echo $twig->render('tooltips.html.twig', $data);

