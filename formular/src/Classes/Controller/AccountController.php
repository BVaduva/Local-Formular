<?php

namespace App\Controller;

use App\Controller\UserManager;
use App\Utility\SessionManager;
use App\Utility\LogManager;

class AccountController
{
    private UserManager $userManager;
    private SessionManager $sessionManager;
    private LogManager $logManager;

    public function __construct(UserManager $userManager, SessionManager $sessionManager, LogManager $logManager)
    {
        $this->userManager = $userManager;
        $this->sessionManager = $sessionManager;
        $this->logManager = $logManager;
    }

    /*****************************************************************
     *                   REGISTER
     ****************************************************************/
    public function register(): void
    {
        $registrationupdateState = $this->userManager->registerNewUser();
        $header = 'register.php';

        if ($registrationupdateState === true) {
            $this->handleSuccessfulRegistration();
        } else {
            $this->handleFailedRegistration($registrationupdateState, $header);
        }
    }


    private function handleSuccessfulRegistration(): void
    {
        $this->sessionManager->set('update_state', 'Registration successful');
        header('Location: login.php');
        exit;
    }

    private function handleFailedRegistration($reason, $header): void
    {
        if ($reason['username_exists'] && $reason['email_exists']) {
            $this->sessionManager->set('error_message', 'Username and Email already taken.');
        } elseif ($reason['username_exists']) {
            $this->sessionManager->set('error_message', 'Username already exists.');
        } elseif ($reason['email_exists']) {
            $this->sessionManager->set('error_message', 'Email already registered.');
        }

        $this->logManager->logError(
            'User could not register.',
            [
                'reason' => $reason,
                'timestampt' => date("d-m-Y H:i:s"),
            ]
        );

        header("Location: $header");
        exit;
    }

    /*****************************************************************
     *                   CREATE
     ****************************************************************/
    public function create(): void
    {
        $this->sessionManager->validateSession();
        session_regenerate_id(true);

        $creationState = $this->userManager->createNewUser();
        $header = '';

        if ($creationState === true) {
            $this->handleSuccesfulCreation();
        } else {
            //can just use handleFailedRegistration?
            $this->handleFailedCreation($creationState, $header);
        }
    }

    private function handleSuccesfulCreation(): void
    {
        $this->sessionManager->set('update_state', 'Successfully created User.');
    }

    //Obsolete?
    private function handleFailedCreation($reason, $header): void
    {
        $this->handleFailedRegistration($reason, $header);
    }

    /*****************************************************************
     *                   DELETE
     ****************************************************************/
    public function deleteUser(): void
    {
        $this->sessionManager->validateSession();
        session_regenerate_id(true);

        $userIdToDelete = $this->userManager->getId() ?? null;

        if ($userIdToDelete === null) {
            $this->handleFailedDelete('No user selected for deletion.');
            return;
        }

        $currentUserId = $this->userManager->getCurrentId();

        if ($currentUserId === $userIdToDelete) {
            $this->handleFailedDelete('You can not delete yourself');
        }

        if ($userIdToDelete !== $currentUserId && $this->userManager->currentIsAdmin()) {
            // Attempt to delete the user
            if ($this->userManager->deleteUser($userIdToDelete)) {
                $this->handleSuccessfulDelete();
            } else {
                $this->handleFailedDelete('Failed to delete user.');
            }
        } else {
            $this->handleFailedDelete('You do not have permission to delete this user.');
        }
    }

    private function handleSuccessfulDelete(): void
    {
        $this->sessionManager->set('delete_state', 'Successfully deleted user.');
        header('Location: show_users.php');
        exit;
    }

    private function handleFailedDelete(string $errorMessage): void
    {
        $this->sessionManager->set('error_message', $errorMessage);
        header('Location: show_users.php');
        exit;
    }

    /*****************************************************************
     *                   UPDATE
     ****************************************************************/
    public function updateUser()
    {
        $this->sessionManager->validateSession();
        session_regenerate_id(true);

        $updateState = $this->userManager->updateUserDetails();
        if ($updateState === true) {
            //echo 'Before success update $updateState = <br>' . $updateState;
            $this->handleSuccessfulUpdate('user');
        } else {
            $this->handleFailedUpdate($updateState, 'user');
        }
    }

    public function updateProfile()
    {
        $this->sessionManager->validateSession();
        session_regenerate_id(true);

        $updateState = $this->userManager->updateUserDetails();

        if ($updateState === true) {
            //echo 'Before success update $updateState = <br>' . $updateState;
            $this->handleSuccessfulUpdate('profile');
        } else {
            $this->handleFailedUpdate($updateState, 'profile');
        }
    }

    public function updateUserPassword()
    {
        $this->sessionManager->validateSession();
        session_regenerate_id(true);

        $updateState = $this->userManager->attemptPasswordReset();

        if ($updateState === true) {
            $this->handleSuccessfulUpdate('password');
        } else {
            $this->handleFailedUpdate($updateState, 'password');
        }
    }

    public function changeRole()
    {
        $this->sessionManager->validateSession();
        session_regenerate_id(true);

        $current_user_id = $this->userManager->getCurrentId();
        if (isset($_GET['role_id'])) {
            $new_role_id = (int) $_GET['role_id'];

            $updateState = $this->userManager->updateUserRole($current_user_id, $new_role_id);
            if ($updateState == true) {
                $this->handleSuccessfulUpdate('role');
            } else {
                $this->handleFailedUpdate($updateState, 'role');
            }
        }
    }

    private function handleSuccessfulUpdate($update): void
    {
        if ($update === 'user') {
            $this->sessionManager->set('update_state', 'Successfully updated user');
            //echo $this->sessionManager->get('update_state');
            header('Location: show_users.php');
            exit;
        }

        if ($update === 'password') {
            $this->sessionManager->set('update_state', 'Password reset successfull. Please login again.');
            header('Location: login.php');
            exit;
        }

        if ($update === 'profile') {
            $this->sessionManager->set('update_state', 'Successfully updated profile. Please login again.');
            header('Location: login.php');
            exit;
        }

        if ($update === 'role') {
            $this->sessionManager->set('update_state', 'Successfully updated role.');
            header('Location: /show_users.php');
            exit;
        }
    }

    private function handleFailedUpdate($reason, $update): void
    {
        if ($update === 'user' || $update === 'profile') {
            if ($reason['username_exists'] && $reason['email_exists']) {
                $this->sessionManager->set('error_message', 'Username and Email already taken.');
            } elseif ($reason['username_exists']) {
                $this->sessionManager->set('error_message', 'Username already exists.');
            } elseif ($reason['email_exists']) {
                $this->sessionManager->set('error_message', 'Email already registered.');
            }
        }

        if ($update === 'password') {
            if ($reason === 'Empty') {
                $this->sessionManager->set('error_message', 'Please fill in all password fields.');
            } elseif ($reason === 'Mismatch') {
                $this->sessionManager->set('error_message', 'New password and confirmation do not match.');
            } elseif ($reason === false) {
                $this->sessionManager->set('error_message', 'Wrong password.');
            } else {
                $this->sessionManager->set('error_message', 'An unexpected error occurred.');
            }
        }

        if ($update === 'role') {
            $this->sessionManager->set('error_message', 'Could not change role');
            header('Location: /show_users.php');
        }
    }
}
