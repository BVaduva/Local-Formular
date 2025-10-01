<?php

// Init: $twig, $sessionManager, $requestHandler, $uiComponent
include __DIR__ . '/../../config/twig_init.php';

$navbar = $uiComponent->getNavbarData();

$popover_top = [
    'size' => 'lg',
    'class' => 'primary',
    'placement' => 'top',
    'content' => 'Big button with top popover.',
    'button_text' => 'Top',
];

$popover_right = [
    'class' => 'secondary',
    'placement' => 'right',
    'content' => 'Basic button with right popover.',
    'button_text' => 'Right',
];

$popover_bottom = [
    'size' => 'sm',
    'class' => 'danger',
    'placement' => 'bottom',
    'content' => 'Small button with bottom popover.',
    'button_text' => 'Bottom',
];

$data = [
    'navbar' => $navbar,
    'top' => $popover_top,
    'right' => $popover_right,
    'bottom' => $popover_bottom,

];

echo $twig->render('popovers.html.twig', $data);
