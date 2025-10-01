<?php

// Init: $twig, $sessionManager, $requestHandler, $uiComponent
include __DIR__ . '/../../config/twig_init.php';

$navbar = $uiComponent->getNavbarData();

$breadcrumbs =
    [
        'previous_page' => '/user_dashboard.php',
        'previous_page_text' => 'Dashboard',
        'current_page' => '/components/breadcrumbs.php',
        'current_page_text' => 'Breadcrumbs'
    ];

$data = [
    'navbar' => $navbar,
    'breadcrumbs' => $breadcrumbs,
];

echo $twig->render('breadcrumbs.html.twig', $data);
