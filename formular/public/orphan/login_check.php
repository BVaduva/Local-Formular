<?php
include __DIR__ . '/user_manager_loader.php';

$user = $userManager->getUsername();
$loginSuccess = $userManager->attemptLogin($user);

$userDetails = $queryHandler->getUserDataByUsername($user);

// CHECK FOR PWD_RESET_PENDING BOOL ON LOGIN
if ($loginSuccess) {
    $sessionManager->set('login_state', 'success');
    $sessionManager->set('username', $user);

    if ($userDetails) {
        $sessionManager->set('role_id', $userDetails['role_id']);
        $sessionManager->set('user_id', $userDetails['id']);
        $sessionManager->set('email', $userDetails['email']);

        $role_id = $userDetails['role_id'];
    }

    if ($userDetails['pwd_reset_pending'] === 1) {
        $sessionManager->set('error_message', 'Please reset your password.');
        header('Location: reset_password_page.php');
        exit;
    }

    if ($role_id < 3) {
        header('Location: show_users.php');
        exit;
    } else {
        header('Location: profile_page.php');
        exit;
    }
} else {
    $sessionManager->set('error_message', 'Wrong credentials.');
    header('Location: login_page.php');
    exit;
}
