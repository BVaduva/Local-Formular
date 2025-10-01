<?php

// Init: $twig, $sessionManager, $requestHandler, $uiComponent
include __DIR__ . '/../../config/twig_init.php';

$navbar = $uiComponent->getNavbarData();

$offcanvas_left = [
    'offcanvas_left' =>
    [
        'offcanvas_id' => 'leftOffcanvas',
        'button_text' => 'Toggle Left',
        'title' => 'Offcanvas Left Title',
        'content' => 'Offcanvas Content: Enable closing by clicking outside; No scrolling',
    ]
];

$offcanvas_right = [
    'offcanvas_right' =>
    [
        'offcanvas_id' => 'rightOffcanvas',
        'button_text' => 'Toggle Right',
        'title' => 'Offcanvas Right Title',
        'content' => 'Offcanvas Content: Disable closing by clicking outside; No scrolling',
        'position' => 'end',
        'button_type' => 'secondary',
        'backdrop' => 'static',
    ]
];

$offcanvas_top = [
    'offcanvas_top' =>
    [
        'offcanvas_id' => 'topOffcanvas',
        'button_text' => 'Toggle Top',
        'title' => 'Offcanvas Top Title',
        'content' => 'Offcanvas Content: Enable closing by clicking outside; Scrolling enabled',
        'position' => 'top',
        'button_type' => 'danger',
        'scroll' => 'true',
    ]
];

$offcanvas_bottom = [
    'offcanvas_bottom' =>
    [
        'offcanvas_id' => 'bottomOffcanvas',
        'button_text' => 'Toggle Bottom',
        'title' => 'Offcanvas Bottom Title',
        'content' => 'Offcanvas Content: Disable closing by clicking outside; Scrolling enabled',
        'position' => 'bottom',
        'button_type' => 'success',
        'scroll' => 'true',
        'backdrop' => 'false',
    ]
];

$data = [
    'navbar' => $navbar,
    'offcanvas_left' => $offcanvas_left,
    'offcanvas_right' => $offcanvas_right,
    'offcanvas_top' => $offcanvas_top,
    'offcanvas_bottom' => $offcanvas_bottom,
];

echo $twig->render('offcanvas.html.twig', $data);
