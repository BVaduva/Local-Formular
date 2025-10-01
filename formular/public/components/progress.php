<?php

// Init: $twig, $sessionManager, $requestHandler, $uiComponent
include __DIR__ . '/../../config/twig_init.php';

$navbar = $uiComponent->getNavbarData();

$progress_base = [
    'label' => 'base',
    'now' => '25',
    'min' => '0',
    'max' => '100',
    'height' => '2',
    'class' => 'primary',
    'text' => '',
];

$progress_text = [
    'label' => 'text',
    'now' => '50',
    'min' => '0',
    'max' => '100',
    'height' => '15',
    'class' => 'secondary',
    'text' => '50% Progress',
];

$progress_stripped = [
    'label' => 'stripped',
    'now' => '25',
    'min' => '0',
    'max' => '100',
    'height' => '10',
    'class' => 'success',
    'text' => '25% Stripped'
];

$progress_animated = [
    'label' => 'animated',
    'now' => '75',
    'min' => '0',
    'max' => '100',
    'height' => '20',
    'class' => 'danger',
    'text' => '75% Animated'
];

$data = [
    'navbar' => $navbar,
    'base' => $progress_base,
    'text' => $progress_text,
    'stripped' => $progress_stripped,
    'animated' => $progress_animated,
];

echo $twig->render('progress.html.twig', $data);
