<?php
include __DIR__ . '/user_manager_loader.php';

// Set the logout message in a cookie before destroying the session
$sessionManager->setCookie('logout_message', 'Successfully logged out.', 7); // Expires in 10 seconds
$sessionManager->destroy();

header('Location: login.php');
exit;
