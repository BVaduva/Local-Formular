<?php

// Init: $twig, $sessionManager, $requestHandler, $uiComponent
include __DIR__ . '/../config/twig_init.php';
// Init: $queryHandler, $userManager
include __DIR__ . '/../src/Classes/Utility/User_Manager_Loader.php';

$tableData = new App\View\TableData($queryHandler, $userManager);
$authController = new App\Controller\AuthController($userManager, $sessionManager, $queryHandler, $logManager);
$accountController = new App\Controller\AccountController($userManager, $sessionManager, $logManager);

if ($requestHandler->isPost() && $requestHandler->getAction() === 'logout') {
    $authController->logout();
    exit;
}

if ($requestHandler->isPost() && [$userManager->currentIsEditor() || $userManager->currentIsAdmin()]) {
    $accountController->updateUser();
}

$authController->checkPermission(2);

$navbar = $uiComponent->getNavbarData();
$alerts = $uiComponent->getAlertsData();
$user_id = $userManager->getId();
$roles = $queryHandler->fetchRoles();
$table = $tableData->fetchSingleUserTableData($user_id);
$users = $table['users'];
$user = $users[0];
$filtered_roles = $userManager->filterRolesByPermission($roles, $user['role_id']);

$input_data = [
    ['type' => 'hidden', 'id' => 'Id', 'name' => 'id', 'value' => $user['id']],
    ['type' => 'text', 'id' => 'Username', 'name' => 'username', 'label' => 'Username', 'value' => $user['username'], 'required' => true],
    ['type' => 'email', 'id' => 'Email', 'name' => 'email', 'label' => 'Email', 'value' => $user['email'], 'required' => true],
    ['type' => 'text', 'id' => 'Comment', 'name' => 'comment', 'label' => 'Comment', 'value' => $user['comment'], 'required' => true],

];

$select_data = [
    [
        'id' => 'roleId',
        'name' => 'role_id',
        'label' => 'Role ID',
        'options' => $filtered_roles,
        'selected' => $user['role_id'],
    ],
    [
        'id' => 'pwdReset',
        'name' => 'pwd_reset_pending',
        'label' => 'Pwd Reset',
        'options' => [
            ['id' => 1, 'name' => 'Yes'],
            ['id' => 0, 'name' => 'No'],
        ],
        'selected' => $user['pwd_reset_pending'],
    ]
];

$buttons = [
    ['text' => 'Update']
];

$data = [
    'form_title' => 'Edit User',
    'form_action' => '',
    'form_method' => 'POST',
    'input_data' => $input_data,
    'select_data' => $select_data,
    'buttons' => $buttons,
    'navbar' => $navbar,
    'alerts' => $alerts,
];

$data = array_merge($table, $data);

echo $twig->render('edit_user.html.twig', $data);
