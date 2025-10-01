<?php

// Init: $twig, $sessionManager, $requestHandler, $uiComponent
include __DIR__ . '/../../config/twig_init.php';

$navbar = $uiComponent->getNavbarData();


$carousel_autoplay = [
    [
        'image' => './../img/dog1.jpg',
        'alt' => 'dog picture',
    ],
    [
        'image' => './../img/dog2.jpg',
        'alt' => 'dog picture',
    ],
    [
        'image' => './../img/dog3.jpg',
        'alt' => 'dog picture',
    ],

];

$carousel_nav = [
    [
        'image' => './../img/dog1.jpg',
        'alt' => 'dog picture',
        'title' => 'Happy Dog',
        'text' => 'A happy dog',
    ],
    [
        'image' => './../img/dog2.jpg',
        'alt' => 'dog picture',
        'title' => 'Shocked Dog',
        'text' => 'What?',
    ],
    [
        'image' => './../img/dog3.jpg',
        'alt' => 'dog picture',
        'title' => 'Sleeping Dog',
        'text' => 'zzZZzzzzZZZzz',
    ],
];

$card_autoplay = [
    'cards' => [
        'title' => 'Autoplay',
        'text' => 'Carousel component with autoplay enabled. No nav buttons.',
    ],
];

$card_nav = [
    'cards' => [
        'title' => 'Manual Navigation',
        'text' => 'Carousel component with nav buttons, title, text and no autoplay feature.'
    ]
];


$data = [
    'navbar' => $navbar,
    'carousel' => $carousel_autoplay,
    'carousel_nav' => $carousel_nav,
    'cards' => $card_autoplay,
    'cards_nav' => $card_nav,
];

echo $twig->render('carousel.html.twig', $data);
