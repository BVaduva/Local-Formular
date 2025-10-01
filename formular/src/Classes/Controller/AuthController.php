<?php

namespace App\Controller;

use App\Controller\UserManager;
use App\Utility\SessionManager;
use App\Model\QueryHandler;
use App\Utility\LogManager;

class AuthController
{
    private UserManager $userManager;
    private SessionManager $sessionManager;
    private QueryHandler $queryHandler;
    private LogManager $logManager;

    public function __construct(UserManager $userManager, SessionManager $sessionManager, QueryHandler $queryHandler, LogManager $logManager)
    {
        $this->userManager = $userManager;
        $this->sessionManager = $sessionManager;
        $this->queryHandler = $queryHandler;
        $this->logManager = $logManager;
    }

    public function login(): void
    {
        $username = $this->userManager->getUsername();

        if ($this->userManager->attemptLogin($username)) {
            session_regenerate_id(true);
            $this->handleSuccessfulLogin($username);
        } else {
            $this->handleFailedLogin();
        }
    }

    public function logout()
    {
        $this->sessionManager->validateSession();
        session_regenerate_id(true);
        $this->sessionManager->setCookie('logout_message', 'Successfully logged out', 7);
        $this->sessionManager->destroy();
        header('Location: login.php');
        exit;


    }

    private function handleSuccessfulLogin(string $username): void
    {
        $userDetails = $this->queryHandler->getUserDataByUsername($username);
        $this->setSessionData($username, $userDetails);

        if ($userDetails && $userDetails['pwd_reset_pending'] === 1) {
            $this->sessionManager->set('error_message', 'Please reset your password.');
            header('Location: /reset_password.php');
            exit;
        }

        $redirectUrl = ($userDetails['role_id'] < 3) ? '/show_users.php' : '/user_dashboard.php';
        header('Location: ' . $redirectUrl);
        exit;
    }

    private function handleFailedLogin(): void
    {
        $this->sessionManager->set('error_message', 'Wrong credentials.');
        header('Location: /login.php');
        exit;
    }

    private function setSessionData(string $username, ?array $userDetails): void
    {
        if (!$this->sessionManager->validateSession()) {
            throw new \Exception('Invalid session; cannot set session data.');
        }

        $this->sessionManager->set('login_state', 'success');
        $this->sessionManager->set('username', $username);

        if ($userDetails) {
            $this->sessionManager->set('role_id', $userDetails['role_id']);
            $this->sessionManager->set('id', $userDetails['id']);
            $this->sessionManager->set('email', $userDetails['email']);
        }
    }

    public function checkPermission($requiredRoleId): void
    {
        $currentRoleId = $this->sessionManager->get('role_id');

        if ($currentRoleId > $requiredRoleId  || $currentRoleId === null) {
            header('Location: /access_denied.php');
            exit;
        }
    }
}
