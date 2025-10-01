<?php

// Init: $twig, $sessionManager, $requestHandler, $uiComponent
include __DIR__ . '/../../config/twig_init.php';

$navbar = $uiComponent->getNavbarData();

$active_buttons = [
    [
        'active_link_text' => 'Active Button',
        'link' => '#',
        'type' => 'primary',
    ],
    [
        'link_text' => 'Button 1',
        'link' => '#',
        'type' => 'primary',
    ],
    [
        'link_text' => 'Button 2',
        'link' => '#',
        'type' => 'primary',
    ],
];

$outlined_buttons = [
    [
        'link_text' => 'Button',
    ],
    [
        'link_text' => 'Button 1',
    ],
    [
        'link_text' => 'Button 2',
    ],
];

$checkbox_buttons = [
    [
        'text' => 'Checkbox',
        'id' => 'Checkbox',
    ],
    [
        'text' => 'Checkbox 1',
        'id' => 'Checkbox1',
    ],
    [
        'text' => 'Checkbox 2',
        'id' => 'Checkbox2',
    ],
];

$data = [
    'active_buttons' => $active_buttons,
    'outlined_buttons' => $outlined_buttons,
    'checkbox_buttons' => $checkbox_buttons,
    'navbar' => $navbar,
];

echo $twig->render('button_groups.html.twig', $data);
