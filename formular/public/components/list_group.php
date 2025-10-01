<?php

// Init: $twig, $sessionManager, $requestHandler, $uiComponent
include __DIR__ . '/../../config/twig_init.php';

$navbar = $uiComponent->getNavbarData();



$data = [
    'navbar' => $navbar,

];

echo $twig->render('list_group.html.twig', $data);
