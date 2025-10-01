<?php

// Init: $twig, $sessionManager, $requestHandler, $uiComponent
include __DIR__ . '/../../config/twig_init.php';

$navbar = $uiComponent->getNavbarData();

$vertical_collapse = [
    'button_text' => 'Vertical Toggle',
    'card_text' => 'Vertical card expand',
    'id' => 'verticalCard',
];

$horizontal_collapse = [
    'button_text' => 'Horizontal Toggle',
    'card_text' => 'Horizontal card expand',
    'id' => 'horizontalCard',
    'horizontal' => true,
];

$multi_collapse = [
    'button1_text' => 'Toggle First',
    'button2_text' => 'Toggle Second',
    'button_multi_text' => 'Toggle Both',
    'card1_text' => 'First Card',
    'card2_text' => 'Second Card',
];

$data = [
    'navbar' => $navbar,
    'vertical_collapse' => $vertical_collapse,
    'horizontal_collapse' => $horizontal_collapse,
    'multi_collapse' => $multi_collapse,
];

echo $twig->render('collapse.html.twig', $data);
