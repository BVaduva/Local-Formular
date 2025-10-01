<?php
// Init: $twig, $sessionManager, $requestHandler, $uiComponent
include __DIR__ . '/../config/twig_init.php';
// Init: $queryHandler, $userManager
include __DIR__ . '/../src/Classes/Utility/User_Manager_Loader.php';

$accountController = new App\Controller\AccountController($userManager, $sessionManager, $logManager);

if ($requestHandler->isPost()) {
    $accountController->updateUserPassword();
}

$alerts = $uiComponent->getAlertsData();
$user = $userManager->getCurrentUser();

$input_data = [
    ['type' => 'hidden', 'id' => 'Id', 'name' => 'id', 'value' => $user['id']],
    ['type' => 'password', 'id' => 'password', 'name' => 'password', 'label' => 'Current Password', 'required' => true],
    ['type' => 'password', 'id' => 'new_password', 'name' => 'new_password', 'label' => 'New Password', 'required' => true],
    ['type' => 'password', 'id' => 'confirm_password', 'name' => 'confirm_password', 'label' => 'Confirm Password', 'required' => true],
];

$buttons = [
    [
        'text' => 'Confirm Reset',
        'class' => 'btn btn-success',
    ]
];

$data = [
    'form_title' => 'Password Reset',
    'form_action' => '',
    'form_method' => 'POST',
    'input_data' => $input_data,
    'buttons' => $buttons,
    'alerts' => $alerts
];

echo $twig->render('password_reset.html.twig', $data);
