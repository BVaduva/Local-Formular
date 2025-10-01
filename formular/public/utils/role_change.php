<?php
// Init: $twig, $sessionManager, $requestHandler, $uiComponent
include __DIR__ . '/../../config/twig_init.php';
// Init: $queryHandler, $userManager
include __DIR__ . '/../../src/Classes/Utility/User_Manager_Loader.php';

// $authController = new App\Controller\AuthController($userManager, $sessionManager, $queryHandler);
$accountController = new App\Controller\AccountController($userManager, $sessionManager, $logManager);
// print_r($_SESSION);

$accountController->changeRole();
?>