<?php

// Init: $twig, $sessionManager, $requestHandler, $uiComponent
include __DIR__ . '/../../config/twig_init.php';

$navbar = $uiComponent->getNavbarData();

$border_spinner = [
    'type' => 'border',
    'color' => 'primary',
];

$grow_spinner = [
    'type' => 'grow',
    'color' => 'secondary',
    'text' => '',
];

$data = [
    'navbar' => $navbar,
    'border' => $border_spinner,
    'grow' => $grow_spinner,
];

echo $twig->render('spinners.html.twig', $data);
