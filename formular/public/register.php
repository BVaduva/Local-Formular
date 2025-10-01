<?php
// Init: $twig, $sessionManager, $requestHandler, $uiComponent
include __DIR__ . '/../config/twig_init.php';
// Init: $queryHandler, $userManager
include __DIR__ . '/../src/Classes/Utility/User_Manager_Loader.php';

$accountController = new App\Controller\AccountController($userManager, $sessionManager, $logManager);

if ($requestHandler->isPost()) {
    $accountController->register();
}

$alerts = $uiComponent->getAlertsData();

$input_data = [
    ['type' => 'text', 'id' => 'userField', 'name' => 'username', 'label' => 'Username', 'placeholder' => 'Enter Username', 'value' => '', 'required' => true],
    ['type' => 'email', 'id' => 'emailField', 'name' => 'email', 'label' => 'Email', 'placeholder' => 'Enter Email', 'value' => '', 'required' => true],
    ['type' => 'password', 'id' => 'passwordField', 'name' => 'password', 'label' => 'Password', 'placeholder' => 'Password', 'value' => '', 'required' => true],
];

$buttons = [
    ['text' => 'Register']
];

$data = [
    'form_title' => 'Registration',
    'form_action' => '',
    'form_method' => 'POST',
    'input_data' => $input_data,
    'buttons' => $buttons,
    'alerts' => $alerts,
];

echo $twig->render('register.html.twig', $data);
