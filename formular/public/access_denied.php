<?php
// Init: $twig, $sessionManager, $requestHandler, $uiComponent
include __DIR__ . '/../config/twig_init.php';

echo $twig->render('access_denied.html.twig');
