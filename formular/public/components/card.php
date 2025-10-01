<?php

// Init: $twig, $sessionManager, $requestHandler, $uiComponent
include __DIR__ . '/../../config/twig_init.php';

$navbar = $uiComponent->getNavbarData();

$card_with_tabs = [
    [
        'title' => 'Definition',
        'content' => 'template engine example that sucks'
    ],
    [
        'title' => 'Word',
        'content' => 'Twig'
    ],
    [
        'title' => 'Example',
        'content' => "{% include 'card_with_tabs.twig' with tabs %} without key:value still taking key:value"
    ],
    [
        'title' => 'Synonyms',
        'content' => 'Help',
    ]
];

$cards = [
    'cards' => [
        'title' => 'A Card',
        'text' => 'Lorem and stuff',
        'card_item' => [
            ['text' => 'Item 1'],
            ['text' => 'Item 2'],
        ],
        'card_link' => [
            ['link' => '#', 'text' => 'Link 1'],
            ['link' => '#', 'text' => 'Links 2'],
        ],
    ]
];

$data = [
    'tabs' => $card_with_tabs,
    'cards' => $cards,
    'navbar' => $navbar,
];

echo $twig->render('card.html.twig', $data);
