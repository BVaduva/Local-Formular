<?php

// Init: $twig, $sessionManager, $requestHandler, $uiComponent
include __DIR__ . '/../../config/twig_init.php';

$navbar = $uiComponent->getNavbarData();

$button = [
    'text' => 'Close Button'
];

$data = [
    'navbar' => $navbar,
    'button' => $button,
];

echo $twig->render('close_button.html.twig', $data);
