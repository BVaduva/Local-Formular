<?php

// Init: $twig, $sessionManager, $requestHandler, $uiComponent
include __DIR__ . '/../../config/twig_init.php';

$navbar = $uiComponent->getNavbarData();

$alerts = [
    [
        'alert_message' => 'This is an alert of type primary.',
        'alert_type' => 'alert-primary',
    ],
    [
        'alert_message' => 'This is an alert of type success.',
        'alert_type' => 'alert-success',
    ],
    [
        'alert_message' => 'This is an alert of type danger.',
        'alert_type' => 'alert-danger',
    ]
];

$data = [
    'navbar' => $navbar,
    'alerts' => $alerts,
];

echo $twig->render('alerts.html.twig', $data);
