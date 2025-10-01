<?php
include __DIR__ . '/user_manager_loader.php';

$registrationSuccess = false;

try {
    // Attempt to register the user and capture the result
    $registrationSuccess = $userManager->registerNewUser();
} catch (Exception $e) {
    // Log the exception or handle it as needed
    $sessionManager->set('error_message', 'An error occurred: ' . $e->getMessage());
}

// Check the registration result and set session message
if ($registrationSuccess) {
    $sessionManager->set('update_state', 'Registration successful');
    header('Location: login_page.php');
    exit;
} else {
    $sessionManager->set('error_message', 'Username or Email already registered.');
    header('Location: register_page.php');
    exit;
}
