<?php

declare(strict_types=1);

namespace App\Model;

use App\Utility\LogManager;

class QueryHandler
{
    private $dbConnection;
    private MyDB $mydb;
    private LogManager $logManager;


    public function __construct(LogManager $logManager)
    {
        $this->mydb = new MyDB();
        $this->logManager = $logManager;
        $this->dbConnection = $this->mydb->getConnection();
    }


    public function getDbConnection()
    {
        return $this->dbConnection;
    }

    /*****************************************************************
     *                   USER (DATA) RETRIEVAL                       
     ****************************************************************/

    public function getUserDataById(int $id): ?array
    {
        $query = "SELECT * FROM users WHERE id=?";
        return $this->executeQuery($query, "i", [$id], true);
    }


    public function getUserDataByUsername(string $username): ?array
    {
        $query = "SELECT * FROM users WHERE username=?";
        return $this->executeQuery($query, "s", [$username], true);
    }


    public function getHashedPassword(int $id): ?string
    {
        $query = "SELECT password FROM users WHERE id=?";
        $result = $this->executeQuery($query, "i", [$id], true);
        return $result['password'] ?? null;
    }

    public function fetchUsers(string $query, ?string $types = null, array $params = []): array
    {
        return $this->executeQuery($query, $types, $params);
    }


    public function fetchRoles(): array
    {
        $query = "SELECT id, name FROM roles";
        return $this->executeQuery($query, null, [], false);
    }

    public function getAllUsernames(): array
    {
        $query = "SELECT username FROM users";
        return $this->executeQuery($query);
    }


    /*****************************************************************
     *                        USER MANAGEMENT                        
     ****************************************************************/

    public function registerUser(string $username, string $email, string $password): mixed
    {
        $this->checkDataExists($username, $email);

        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        return $this->executeQuery($query, "sss", [$username, $email, $password], false);
    }

    public function createUser(string $username, string $email, string $password, int $role, bool $pwd_reset_pending): bool
    {
        $this->checkDataExists($username, $email);

        $query = "INSERT INTO users (username, email, password, role_id, pwd_reset_pending) VALUES (?, ?, ?, ?, ?)";
        return $this->executeQuery($query, "sssii", [$username, $email, $password, $role, $pwd_reset_pending]);
    }


    public function updateUserData(string $username, string $email, string $comment, int $role_id, mixed $pwd_reset_pending, int $id): bool
    {
        $this->checkDataExists($username, $email);

        $query = "UPDATE users SET username = ?, email = ?, comment = ?, role_id = ?, pwd_reset_pending = ? WHERE id = ?";
        $pwd_reset_pending_int = $pwd_reset_pending ? 1 : 0;
        $success = $this->executeQuery($query, "sssiii", [$username, $email, $comment, $role_id, $pwd_reset_pending_int, $id], false);

        return $success;
    }

    public function updateUserPassword(string $username, string $hashed_password)
    {
        $query = "UPDATE users SET password = ?, pwd_reset_pending = ? WHERE username = ?";
        $success = $this->executeQuery($query, "sis", [$hashed_password, 0, $username], false);

        return $success;
    }


    public function deleteUser(int $id): bool
    {
        $query = "DELETE FROM users WHERE id = ?";
        return $this->executeQuery($query, "i", [$id], false);
    }


    /*****************************************************************
     *                   DATABASE UTILITIES                           
     *****************************************************************/

    public function getUserTableColumnNames(): array
    {
        $query = "SHOW COLUMNS FROM users";
        $columns = $this->executeQuery($query, null, [], false);

        if (!is_array($columns)) {
            throw new \Exception("Failed to retrieve user table column names.");
        }

        return array_column($columns, 'Field');
    }


    private function executeQuery(string $query, ?string $types = null, array $params = [], bool $singleResult = false): mixed
    {
        $stmt = $this->dbConnection->prepare($query);
        if (!$stmt) {
            throw new \Exception("Failed to prepare statement: " . $this->dbConnection->error);
        }

        // if ($types !== null && !empty($params)) {
        //     if (strlen($types) !== count($params)) {
        //         throw new \Exception("Number of type characters does not match number of parameters.");
        //     }

        // Only bind parameters if types and params are provided and not empty
        if (!empty($params)) {
            if ($types === null || strlen($types) !== count($params)) {
                throw new \Exception("Number of type characters does not match number of parameters.");
            }
            error_log("Binding params with types: $types and params: " . json_encode($params));

            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();

        // Handle SELECT queries
        if (str_starts_with(strtoupper($query), 'SELECT') || str_starts_with(strtoupper($query), 'SHOW')) {
            $result = $stmt->get_result();

            if ($result === false) {
                throw new \Exception("Query failed: " . $stmt->error);
            }

            $data = $singleResult ? $result->fetch_assoc() : $this->fetchAll($result);
            $stmt->close();

            return $data;
        } else {
            // Handle non-SELECT queries (UPDATE, INSERT, DELETE) by returning true
            $stmt->close();
            return true;
        }
    }

    private function queryExistingData(string $username, string $email): array
    {
        $queryUsername = "SELECT COUNT(*) AS count FROM users WHERE username = ?";
        $resultUsername = $this->executeQuery($queryUsername, "s", [$username], true);

        $queryEmail = "SELECT COUNT(*) AS count FROM users WHERE email = ?";
        $resultEmail = $this->executeQuery($queryEmail, "s", [$email], true);

        return [
            'username_exists' => $resultUsername['count'] > 0,
            'email_exists' => $resultEmail['count'] > 0
        ];
    }

    public function checkDataExists(string $username, string $email): mixed
    {
        $dataExists = $this->queryExistingData($username, $email);

        if ($dataExists['username_exists'] || $dataExists['email_exists']) {
            return $dataExists;
        }

        return null;
    }


    private function fetchAll($result): array
    {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }


    public function close(): void
    {
        if ($this->dbConnection) {
            $this->dbConnection->close();
        }
    }
}
