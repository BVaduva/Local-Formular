<?php

// Init: $twig, $sessionManager, $requestHandler, $uiComponent
include __DIR__ . '/../config/twig_init.php';
// Init: $queryHandler, $userManager
include __DIR__ . '/../src/Classes/Utility/User_Manager_Loader.php';

$tableData = new App\View\TableData($queryHandler, $userManager);
$authController = new App\Controller\AuthController($userManager, $sessionManager, $queryHandler, $logManager);
$accountController = new \App\Controller\AccountController($userManager, $sessionManager, $logManager);

if ($requestHandler->isPost() && $requestHandler->getAction() === 'logout') {
    $authController->logout();
    exit;
}

if ($requestHandler->isPost() && $requestHandler->getAction() === 'delete_user') {
    $accountController->deleteUser();
}

if ($requestHandler->isPost() && $requestHandler->getAction() === 'create_user') {
    $accountController->create();
}

$authController->checkPermission(2);

$navbar = $uiComponent->getNavbarData();
$alerts = $uiComponent->getAlertsData();
$table = $tableData->fetchUserTableData();
$user = $userManager->getCurrentUser();
$roles = $queryHandler->fetchRoles();
$filtered_roles = $userManager->filterRolesByPermission($roles, $user['role_id']);

$input_data = [
    ['type' => 'text', 'id' => 'Username', 'name' => 'username', 'label' => 'Username', 'required' => true],
    ['type' => 'email', 'id' => 'Email', 'name' => 'email', 'label' => 'Email', 'required' => true],
    ['type' => 'password', 'id' => 'password', 'name' => 'password', 'label' => 'Password', 'required' => true],

];

$select_data = [
    [
        'id' => 'roleId',
        'name' => 'role_id',
        'label' => 'Role',
        'options' => $filtered_roles,
        'selected' => $roles[2]['id'],
    ]
];

$buttons = [
    [
        'text' => 'Create',
        'id' => 'btn-create',
        'hidden' => [
            'action' => 'create_user'
        ],
    ]
];

$data = [
    'form_action' => '',
    'form_method' => 'POST',
    'input_data' => $input_data,
    'select_data' => $select_data,
    'buttons' => $buttons,
    'navbar' => $navbar,
    'alerts' => $alerts,
];

$data = array_merge($data, $table);

echo $twig->render('show_users.html.twig', $data);
