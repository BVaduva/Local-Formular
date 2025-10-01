<?php

declare(strict_types=1);

include __DIR__ . '/../../vendor/autoload.php';

use Formular\QueryHandler;
use Formular\RequestHandler;
use Formular\SessionManager;


$requestHandler = new App\RequestHandler();
$queryHandler = new App\QueryHandler();
$sessionManager = new App\SessionManager();

// Loop to add multiple users
for ($i = 4; $i <= 23; $i++) { // 4 to 23 to create 20 users starting from user4
    $username = "user" . $i;
    $email = "u{$i}@x.de";
    $password = $username; // Password is the same as the username (e.g., user4, user5, etc.)

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $success = $queryHandler->registerUser($username, $email, $hashedPassword);

    if ($success) {
        echo "User $username added successfully.<br>";
    } else {
        echo "Failed to add user $username. User might already exist.<br>";
    }
}
