 <?php
    include __DIR__ . '/user_manager_loader.php';

    if ($sessionManager->get('login_state') !== 'success') {
        header('Location: login_page.php');
        exit;
    }

    //$resetPasswords = $userManager->getResetPasswords();

    /*$result = $userManager->attemptPasswordReset(
    $resetPasswords['password'],
    $resetPasswords['new_password'],
    $resetPasswords['confirm_password']
);*/

    $result = $userManager->attemptPasswordReset();

    if ($result === "Empty") {
        $sessionManager->set('error_message', 'Please fill in all password fields.');
    } elseif ($result === "Mismatch") {
        $sessionManager->set('error_message', 'New password and confirmation do not match.');
    } elseif ($result === true) {
        $sessionManager->set('update_state', 'Password reset successfull. Please login again.');
        header('Location: login.php');
        exit;
    } elseif ($result === false) {
        $sessionManager->set('error_message', "Wrong password.");
    } else {
        $sessionManager->set('error_message', 'An unexpected error occurred.');
    }

    header('Location: reset_password.php');
    exit;
