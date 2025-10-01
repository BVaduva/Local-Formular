<?php

// Init: $twig, $sessionManager, $requestHandler, $uiComponent
include __DIR__ . '/../../config/twig_init.php';

$navbar = $uiComponent->getNavbarData();

$badges =
    [
        [
            'button_class' => 'success',
            'badge_class' => 'primary',
            'button_text' => 'Badge + Counter',
            'badge_text' => '7',
        ],
        [
            'button_class' => 'primary',
            'badge_class' => 'secondary',
            'button_text' => 'Badge + Pill',
            'pill_class' => 'danger',
            'pill_hidden_msg' => 'unread messages'
        ],
    ];

$data = [
    'navbar' => $navbar,
    'badges' => $badges,
];

echo $twig->render('badges.html.twig', $data);
