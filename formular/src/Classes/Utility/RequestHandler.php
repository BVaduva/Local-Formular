<?php

declare(strict_types=1);

namespace App\Utility;

use App\Utility\LogManager;

class RequestHandler
{
    public $username;
    public $password;
    public $email;
    public $comment;
    public $id;
    public $role_id;
    public $pwd_reset_pending;
    public $new_password;
    public $confirm_password;
    private LogManager $logManager;

    private $initialized = false;

    public function __construct(LogManager $logManager)
    {
        $this->logManager = $logManager;

        if (!$this->initialized) {
            $this->initialize();
            $this->initialized = true;
        }
    }

    private function initialize()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->username = isset($_POST['username']) ? $_POST['username'] : null;
            $this->password = isset($_POST['password']) ? $_POST['password'] : null;
            $this->email = isset($_POST['email']) ? $_POST['email'] : null;
            $this->comment = isset($_POST['comment']) ? $_POST['comment'] : null;
            $this->id = isset($_POST['id']) ? (int) $_POST['id'] : null;
            $this->role_id = isset($_POST['role_id']) ? (int) $_POST['role_id'] : null;
            $this->pwd_reset_pending = isset($_POST['pwd_reset_pending']) ? $_POST['pwd_reset_pending'] === '1' : false;
            $this->new_password = isset($_POST['new_password']) ? $_POST['new_password'] : null;
            $this->confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : null;
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->username = isset($_GET['username']) ? $_GET['username'] : null;
            $this->password = isset($_GET['password']) ? $_GET['password'] : null;
            $this->email = isset($_GET['email']) ? $_GET['email'] : null;
            $this->comment = isset($_GET['comment']) ? $_GET['comment'] : null;
            $this->id = isset($_GET['id']) ? (int) $_GET['id'] : null;
            $this->role_id = isset($_GET['role_id']) ? (int) $_GET['role_id'] : null;
            $this->pwd_reset_pending = isset($_GET['pwd_reset_pending']) ? $_GET['pwd_reset_pending'] === '1' : false;
            $this->new_password = isset($_GET['new_password']) ? $_GET['new_password'] : null;
            $this->confirm_password = isset($_GET['confirm_password']) ? $_GET['confirm_password'] : null;
        }
    }

    public function getPost(string $key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }

    public function getGet(string $key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    public function getAllPost(): array
    {
        return $_POST;
    }

    public function getAllGet(): array
    {
        return $_GET;
    }

    public function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public function isGet(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    public function getAction(): ?string
    {
        return $this->getPost('action') ?? $this->getGet('action');
    }
}
