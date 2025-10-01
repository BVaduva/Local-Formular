<?php

declare(strict_types=1);

namespace App\Controller;

use App\Utility\RequestHandler;
use App\Model\QueryHandler;
use App\Utility\SessionManager;
use App\Utility\LogManager;

class UserManager
{
    private RequestHandler $requestData;
    private QueryHandler $queryHandler;
    private SessionManager $sessionManager;
    private LogManager $logManager;
    private $username;
    private $email;
    private $password;
    private $comment;
    private $id;
    private $role_id;
    private $pwd_reset_pending;

    const ROLE_ADMIN = 1;
    const ROLE_EDITOR = 2;
    const ROLE_USER = 3;

    public function __construct(RequestHandler $requestData, QueryHandler $queryHandler, SessionManager $sessionManager, LogManager $logManager)
    {
        $this->requestData = $requestData;
        $this->queryHandler = $queryHandler;
        $this->sessionManager = $sessionManager;
        $this->logManager = $logManager;
        $this->initializeUserData();

        //-------------------DEBUGGING---------------------
        // var_dump("UserManager initialized with: " .
        //     "Username={$this->username}, " .
        //     "Email={$this->email}, " .
        //     "Comment={$this->comment}, " .
        //     "ID={$this->id}, " .
        //     "Role ID={$this->role_id}, " .
        //     "Pwd Reset Pending={$this->pwd_reset_pending}");
    }

    private function initializeUserData(): void
    {
        // Initialize user data from request if available
        $this->username = $this->requestData->username ?? null;
        $this->email = $this->requestData->email ?? null;
        $this->password = $this->requestData->password ?? null;
        $this->comment = $this->requestData->comment ?? null;
        $this->id = $this->requestData->id ?? null;
        $this->role_id = $this->requestData->role_id ?? null;
        $this->pwd_reset_pending = $this->requestData->pwd_reset_pending ?? null;

        // Load user from database if ID is provided and username is not yet set
        if ($this->id !== null && $this->username === null) {
            $this->loadUserFromDatabase($this->id);
        }
    }


    /*****************************************************************
     *                  USER OPERATIONS
     ****************************************************************/

    public function attemptLogin(string $username): bool
    {
        $data = $this->queryHandler->getUserDataByUsername($username);
        $id = $data['id'];
        $hashedPassword = $this->queryHandler->getHashedPassword($id);

        if ($hashedPassword === null) {
            return false;
        }

        return $this->verifyPassword($this->password, $hashedPassword);
    }

    public function registerNewUser(): mixed
    {
        // If exists, return array['username_exists' => true], ['email_exists' => true], both or null.
        return $this->queryHandler->checkDataExists($this->username, $this->email)
            ?? $this->queryHandler->registerUser(
                $this->username,
                $this->email,
                $this->hashPassword()
            );
    }


    public function createNewUser(): mixed
    {
        return $this->queryHandler->checkDataExists($this->username, $this->email)
            ?? $this->queryHandler->createUser(
                $this->username,
                $this->email,
                $this->hashPassword(),
                $this->role_id,
                true,
            );
    }

    public function attemptPasswordReset(): mixed
    {
        $password = $this->password;
        $new_password = $this->requestData->new_password ?? null;
        $confirm_password = $this->requestData->confirm_password ?? null;

        if (empty($new_password) || empty($confirm_password) || empty($password)) {
            return "Empty";
        }

        if ($new_password !== $confirm_password) {
            return "Mismatch";
        }

        $hashedPassword = $this->queryHandler->getHashedPassword($this->id);

        if ($this->verifyPassword($password, $hashedPassword)) {
            $new_hashed_pwd = $this->hashPassword($new_password);
            $this->queryHandler->updateUserPassword($this->username, $new_hashed_pwd);
            return true;
        } else {
            return false;
        }
    }

    public function updateUserDetails(): mixed
    {
        if ($this->id === null) {
            return false;
        }

        $target_user_data = $this->queryHandler->getUserDataById($this->id);

        // Store difference in POST data
        $data_changed =
            $this->username !== $target_user_data['username']
            ||
            $this->email !== $target_user_data['email'];

        if ($data_changed) {
            // Check DB for same data as POST data.
            $data_exists = $this->queryHandler->checkDataExists($this->username, $this->email);

            if ($data_exists) {
                if (
                    // If username exists and target+post username are not the same.
                    ($data_exists['username_exists'] === true && $this->username !== $target_user_data['username'])
                    ||
                    ($data_exists['email_exists'] === true && $this->email !== $target_user_data['email'])
                ) {
                    return $data_exists;
                }
            }
        }

        return $this->queryHandler->updateUserData(
            $this->username,
            $this->email,
            $this->comment,
            $this->role_id,
            $this->pwd_reset_pending,
            $this->id,
        );
    }

    public function updateUserRole(int $user_id, int $new_role_id,): bool
    {
        if ($this->id === null) {
            $this->loadUserFromDatabase($user_id);
            if ($this->id === null) {
                return false;
            } 
        }
        if ($new_role_id !== $this->role_id) {
            $this->sessionManager->set('role_id', $new_role_id);
            return $this->queryHandler->updateUserData(
                $this->username,
                $this->email,
                $this->comment,
                $new_role_id,
                $this->pwd_reset_pending,
                $this->id,
            );
        }
        else {
            return false;
        }
    }

    public function deleteUser()
    {
        $id = $this->getId();
        if ($id !== null) {
            return $this->queryHandler->deleteUser($id);
        }
        return false;
    }


    /*****************************************************************
     *                   GET DATA
     ****************************************************************/

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrentId(): ?int
    {
        return $this->sessionManager->get('id');
    }

    public function getRoleId(): ?int
    {
        return $this->role_id;
    }

    public function getCurrentRoleId(): ?int
    {
        return $this->sessionManager->get('role_id');
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getCurrentUsername(): ?int
    {
        return $this->sessionManager->get('username');
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getCurrentEmail(): ?int
    {
        return $this->sessionManager->get('email');
    }

    // Unnecessary?
    public function getResetPasswords(): ?array
    {
        return [
            'password' => $this->password,
            'new_password' => $this->requestData->new_password ?? null,
            'confirm_password' => $this->requestData->confirm_password ?? null,
        ];
    }

    public function getCurrentUser(): ?array
    {
        return [
            'id' => $this->sessionManager->getUserId() ?? null,
            'username' => $this->sessionManager->getUsername() ?? null,
            'email' => $this->sessionManager->getEmail() ?? null,
            'role_id' => $this->sessionManager->getRoleId() ?? null,
            'pwd_reset_pending' => $this->sessionManager->getPwdResetPending() ?? null,
        ];
    }


    /*****************************************************************
     *                   LOAD DATA
     ****************************************************************/

    private function loadUserFromDatabase(int $id): void
    {
        $userData = $this->queryHandler->getUserDataById($id);
        $this->setUserData($userData);
    }

    private function setUserData(?array $userData): void
    {
        if ($userData) {
            $this->id = $userData['id'] ?? null;
            $this->username = $userData['username'] ?? null;
            $this->email = $userData['email'] ?? null;
            $this->comment = $userData['comment'] ?? null;
            $this->role_id = $userData['role_id'] ?? null;
            $this->pwd_reset_pending = $userData['pwd_reset_pending'] ?? null;
        }
    }

    /*****************************************************************
     *                  UTILITIES
     ****************************************************************/
    // Could be private?
    public function hashPassword(string $password = null): string
    {
        $password_to_hash = $password ?? $this->password;
        return password_hash($password_to_hash, PASSWORD_DEFAULT);
    }

    // Could be private?
    public function verifyPassword(string $plainPassword, string $hashedPassword): bool
    {
        return password_verify($plainPassword, $hashedPassword);
    }

    public function filterRolesByPermission(array $roles, int $userRoleId): array
    {
        return array_filter($roles, function ($role) use ($userRoleId) {
            return $role['id'] >= $userRoleId;
        });
    }

    /*****************************************************************
     *                  ROLE CHECKS
     ****************************************************************/

    public function targetIsEditor(): bool
    {
        return $this->getRoleId() === self::ROLE_EDITOR;
    }

    public function targetIsAdmin(): bool
    {
        return $this->getRoleId() === self::ROLE_ADMIN;
    }

    public function currentIsEditor(): bool
    {
        return $this->getCurrentRoleId() === self::ROLE_EDITOR;
    }

    public function currentIsAdmin(): bool
    {
        return $this->getCurrentRoleId() === self::ROLE_ADMIN;
    }
}
