<?php

// Init: $twig, $sessionManager, $requestHandler, $uiComponent
include __DIR__ . '/../../config/twig_init.php';

$alerts = $uiComponent->getAlertsData();
$navbar = $uiComponent->getNavbarData();

$accordion = [
    'items' => ['title' => 'Accordion Title - Press me!', 'content' => "This is an accordion"],
];

$data = [
    'navbar' => $navbar,
    'alerts' => $alerts,
    'items' => $accordion,
];

echo $twig->render('accordion.html.twig', $data);
