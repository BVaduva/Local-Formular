<?php

namespace App\View;

use App\Model\QueryHandler;
use App\Utility\RequestHandler;
use App\Controller\UserManager;

class TableData
{
    private $queryHandler;
    private $userManager;

    public function __construct(QueryHandler $queryHandler, UserManager $userManager)
    {
        $this->queryHandler = $queryHandler;
        $this->userManager = $userManager;
    }

    public function fetchUserTableData()
    {
        $role_id = $this->userManager->getCurrentRoleId();
        $users = $this->queryHandler->fetchUsers(
            "SELECT 
                u.id,
                u.created,
                u.updated,
                u.username,
                u.email,
                u.password,
                u.comment,
                u.role_id,
                u.pwd_reset_pending,
                r.name AS role_name
            FROM 
                users AS u
            JOIN 
                roles AS r ON u.role_id = r.id"
        );

        return [
            'role_id' => $role_id,
            'columnNames' => $this->getUserTableColumnNames(),
            'users' => $users,
        ];
    }

    public function fetchSingleUserTableData(int $user_id)
    {
        $user = $this->queryHandler->getUserDataById($user_id);

        return [
            'columnNames' => $this->getUserTableColumnNames(),
            'users' => [$user],
        ];
    }

    private function getUserTableColumnNames()
    {
        return $this->queryHandler->getUserTableColumnNames();
    }
}
