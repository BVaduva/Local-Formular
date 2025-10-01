<?php
// Init: $twig, $sessionManager, $requestHandler, $uiComponent
include __DIR__ . '/../config/twig_init.php';

$buttons = [
    'buttons' => [
        ['text' => 'Register', 'href' => 'register.php'],
        ['text' => 'Login', 'href' => 'login.php']
    ]
];

// Render the template with data
echo $twig->render('index.html.twig', $buttons);
