<?php
include __DIR__ . '/user_manager_loader.php';

if (!$sessionManager->has('login_state') || $sessionManager->get('login_state') !== 'success') {
    header('Location: access_denied.php');
    exit;
}

$userIdToDelete = $userManager->getId();

if ($userIdToDelete !== null) {

    if ($userIdToDelete === $userManager->getId() || $userManager->isAdmin()) {

        if ($userManager->deleteUser($userIdToDelete)) {
            $sessionManager->set('delete_state', 'Successfully deleted user.');
        } else {
            $sessionManager->set('error_message', 'Failed to delete user.');
        }
    } else {
        $sessionManager->set('error_message', 'You do not have permission to delete this user.');
    }
} else {
    $sessionManager->set('error_message', 'No user selected for deletion.');
}

header('Location: show_users.php');
exit;
