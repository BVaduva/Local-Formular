<?php
include __DIR__ . '/user_manager_loader.php';

$creationSuccess = false;

try {
    // Attempt to register the user and capture the result
    $creationSuccess = $userManager->createNewUser();
} catch (Exception $e) {
    // Log the exception or handle it as needed
    $sessionManager->set('error_message', 'An error occurred: ' . $e->getMessage());
}

// Check the registration result and set session message
if ($creationSuccess) {
    $sessionManager->set('update_state', 'Successfully created User.');
    header('Location: show_users.php');
    exit;
} else {
    $sessionManager->set('error_message', 'Username or Email already exist.');
    header('Location: show_users.php');
    exit;
}
