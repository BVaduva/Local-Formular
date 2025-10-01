<?php
include __DIR__ . '/user_manager_loader.php';

$current_user = $userManager->getCurrentUser();
$target_username = $userManager->getUsername();
$target_email = $userManager->getEmail();
$success = $userManager->updateUserDetails();

if ($success) {
    if ($current_user['role_id'] === 3) {
        $sessionManager->set('update_state', 'Successfully updated profile');
        $sessionManager->set('username', $target_username);
        $sessionManager->set('email', $target_email);
        header('Location: profile_page.php');
        exit;
    } else {
        $sessionManager->set('update_state', 'Successfully updated user');
    }
} elseif (!$success && $current_user['role_id'] === 3) {
    $sessionManager->set('error_message', 'Username or Email already exist.');
    header('Location: profile_page.php');
    exit;
} else {
    $sessionManager->set('error_message', 'Something went wrong');
}

header('Location: show_users.php');
exit;
