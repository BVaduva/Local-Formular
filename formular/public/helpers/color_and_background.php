<?php

// Init: $twig, $sessionManager, $requestHandler, $uiComponent
include __DIR__ . '/../../config/twig_init.php';

$navbar = $uiComponent->getNavbarData();

$buttons = [
    [
        'type' => 'button',
        'text' => 'Primary',
        'class' => 'btn btn-primary',
    ],
    [
        'type' => 'button',
        'text' => 'Secondary LARGE',
        'class' => 'btn btn-secondary btn-lg',
    ],
    [
        'type' => 'button',
        'text' => 'Success small',
        'class' => 'btn btn-success btn-sm',
    ],
];

$buttons_outline = [
    [
        'type' => 'button',
        'text' => 'Primary',
        'class' => 'btn btn-outline-primary',
    ],
    [
        'type' => 'button',
        'text' => 'Secondary',
        'class' => 'btn btn-outline-secondary',
    ],
    [
        'type' => 'button',
        'text' => 'Success',
        'class' => 'btn btn-outline-success',
    ],
];

$data = [
    'navbar' => $navbar,
    'buttons' => $buttons,
    'buttons_outline' => $buttons_outline,
    'wrapper_class' => 'bg-primary text-white'
];

echo $twig->render('color_and_background.html.twig', $data);

