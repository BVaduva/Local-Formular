<?php

// Init: $twig, $sessionManager, $requestHandler, $uiComponent
include __DIR__ . '/../../config/twig_init.php';

$navbar = $uiComponent->getNavbarData();

$dropdown = [
    'dropdown_label' => 'Dropdown Button',
    'dropdown_items' => [
        ['href' => '#', 'label' => 'Item 1'],
        ['href' => '#', 'label' => 'Item 2'],
        ['href' => '#', 'label' => 'Item 3'],
    ]
];

$data = [
    'navbar' => $navbar,
    'dropdown' => $dropdown,
];

echo $twig->render('clearfix.html.twig', $data);

