<?php
// Init: $twig, $sessionManager, $requestHandler, $uiComponent
include __DIR__ . '/../config/twig_init.php';
// Init: $queryHandler, $userManager
include __DIR__ . '/../src/Classes/Utility/User_Manager_Loader.php';

$authController = new App\Controller\AuthController($userManager, $sessionManager, $queryHandler, $logManager);

if ($requestHandler->isPost()) {
    $authController->login();
    exit;
}

$alerts = $uiComponent->getAlertsData();

$input_data = [
    ['type' => 'text', 'id' => 'Username', 'name' => 'username', 'label' => 'Username', 'placeholder' => 'Enter Username', 'value' => '', 'required' => true],
    ['type' => 'password', 'id' => 'Password', 'name' => 'password', 'label' => 'Password', 'placeholder' => 'Password', 'value' => '', 'required' => true],
];

$buttons = [
    ['text' => 'Login']
];

$data = [
    'form_title' => 'Login Form',
    'form_action' => '',
    'form_method' => 'POST',
    'input_data' => $input_data,
    'buttons' => $buttons,
    'alerts' => $alerts,
];

echo $twig->render('login.html.twig', $data);
