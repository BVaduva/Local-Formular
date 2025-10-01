<?php

// Init: $twig, $sessionManager, $requestHandler, $uiComponent
include __DIR__ . '/../../config/twig_init.php';

$navbar = $uiComponent->getNavbarData();

$tabs = [
    'items' =>
    ['link' => '#', 'text' => 'Home', 'active' => true],
    ['link' => '#', 'text' => 'Profile',],
    ['link' => '#', 'text' => 'Contact',]
];

$pills = [
    'items' =>
    ['link' => '#', 'text' => 'Home', 'active' => true],
    ['link' => '#', 'text' => 'Profile',],
    ['link' => '#', 'text' => 'Contact',]
];

$dropdown_pills = [
    'items' => [
        ['link' => '#', 'text' => 'Home', 'active' => true],
        [
            'text' => 'Dropdown',
            'dropdown_items' => [
                ['href' => '#', 'label' => 'Action1'],
                ['href' => '#', 'label' => 'Action 2'],
            ],
            'button_type' => 'secondary',
        ],
    ]
];

$data = [
    'navbar' => $navbar,
    'nav_tabs' => $tabs,
    'nav_pills' => $pills,
    'nav_dropdown' => $dropdown_pills,
];

echo $twig->render('nav_tabs.html.twig', $data);
