<?php
// Include twig_init.php to ensure shared objects are available
include __DIR__ . '/../../../config/twig_init.php';


// Create the missing object(s)
$queryHandler = new \App\Model\QueryHandler($logManager);

// Use the already available $sessionManager and $requestHandler directly
$userManager = new \App\Controller\UserManager($requestHandler, $queryHandler, $sessionManager, $logManager);

// Optionally, you can still return the created object(s) if needed elsewhere
// return $userManager;
