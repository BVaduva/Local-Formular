<?php

// Init: $twig, $sessionManager, $requestHandler, $uiComponent
include __DIR__ . '/../../config/twig_init.php';

$navbar = $uiComponent->getNavbarData();

$toast_data = [
    [
        'id' => 'liveToast1',
        'title' => 'Header title',
        'message' => 'Toast with header. Autohide after 5s.',
        'header_enabled' => true,
        'delay' => 5000,
        'button_text' => 'Show Header Toast',
    ],
    [
        'id' => 'liveToast2',
        'message' => 'Toast without header. No autohide.',
        'header_enabled' => false,
        'autohide' => 'false',
        'delay' => 5000,
        'button_text' => 'Show No Header Toast',
    ],
];


$data = [
    'navbar' => $navbar,
    'toast_data' => $toast_data
];

echo $twig->render('toasts.html.twig', $data);

