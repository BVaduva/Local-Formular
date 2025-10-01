<?php

// Init: $twig, $sessionManager, $requestHandler, $uiComponent
include __DIR__ . '/../../config/twig_init.php';

$navbar = $uiComponent->getNavbarData();

$modal_base = [
    'static' => false,
    'button_text' => 'Non-Static',
    'modal_id' => 'modalBase',
    'title' => 'Modal 1',
    'content' => 'Basic Modal',
    'buttons' => [
        [
            'dismiss' => true,
            'class' => 'secondary',
            'text' => 'Can close',
        ],
        [
            'dismiss' => true,
            'class' => 'primary',
            'text' => 'Can close',
        ],
    ],
];

$modal_static = [
    'modal_button' => 'secondary',
    'button_text' => 'Static',
    'modal_id' => 'modalStatic',
    'title' => 'Modal 2',
    'static' => true,
    'content' => 'Static Modal',
    'buttons' => [
        [
            'class' => 'secondary',
            'text' => 'Can close',
            'dismiss' => true,
        ],
        [
            'class' => 'primary',
            'text' => 'Can not close',
        ],
    ],
];

$modal_scroll = [
    'modal_button' => 'success',
    'button_text' => 'Scroll',
    'modal_id' => 'modalScroll',
    'scrollable' => true,
    'title' => 'Modal 3',
    'static' => false,
    'content' => '<p>This is a long static content...</p>' . str_repeat('<p>More content...</p>', 20),
    'buttons' => [
        [
            'class' => 'secondary',
            'text' => 'Can close',
            'dismiss' => true,
        ],
        [
            'class' => 'primary',
            'text' => 'Can not close',
        ],
    ],
];

$modal_popover = [
    'modal_button' => 'danger',
    'button_text' => 'Popover',
    'modal_id' => 'modalPopover',
    'title' => 'Modal 4',
    'static' => false,
    'body_header' => 'Popover Example',
    'content' => 'Popover Modal',
    'buttons' => [
        [
            'class' => 'secondary',
            'text' => 'Can close',
            'dismiss' => true,
        ],
        [
            'class' => 'primary',
            'text' => 'Can not close',
        ],
    ],
];

$modal_tooltip = [
    'modal_button' => 'warning',
    'button_text' => 'Tooltip',
    'modal_id' => 'modalTooltip',
    'title' => 'Modal 5',
    'static' => false,
    'body_header' => 'Tooltip Example',
    'content' => 'Tooltip Modal',
    'buttons' => [
        [
            'class' => 'secondary',
            'text' => 'Can close',
            'dismiss' => true,
        ],
        [
            'class' => 'primary',
            'text' => 'Can not close',
        ],
    ],
];

$data = [
    'navbar' => $navbar,
    'base' => $modal_base,
    'static' => $modal_static,
    'scroll' => $modal_scroll,
    'popover' => $modal_popover,
    'tooltip' => $modal_tooltip,
];

echo $twig->render('modal.html.twig', $data);
