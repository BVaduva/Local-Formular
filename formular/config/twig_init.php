<?php

use App\Utility\SessionManager;
use App\Utility\RequestHandler;
use App\View\UIComponent;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Utility\LogManager;


// Autoload Composer classes
require_once __DIR__ . '/../vendor/autoload.php';

// Create a Twig loader to specify the location of templates
$loader = new FilesystemLoader([
    __DIR__ . '/../templates',
    __DIR__ . '/../templates/Components',
    __DIR__ . '/../templates/Forms',
    __DIR__ . '/../templates/Helpers',
    __DIR__ . '/../templates/View',
    __DIR__ . '/../templates/View/Components',
    __DIR__ . '/../templates/View/Helpers',
]);

$twig = new Environment($loader, [
    'cache' => __DIR__ . '/../var/cache/twig',
    'debug' => true,  // Ensure debugging is enabled
]);

$twig->addExtension(new \Twig\Extension\DebugExtension());

$logfile = __DIR__ . '/../public/utils/error_log.txt';

// Create instances of SecuirtyHeaders, SessionManager, RequestHandler and UIComponent
$securityHeaders = new \App\Utility\SecurityHeaders();
$securityHeaders->setHeaders();
$logManager = new LogManager($logfile);
$sessionManager = new SessionManager($logManager);
$requestHandler = new RequestHandler($logManager);
$uiComponent = new UIComponent($twig, $sessionManager, $logManager);


//$base_url = str_replace('./public', '', dirname($_SERVER['SCRIPT_NAME']));

// Share data with Twig globally
$twig->addGlobal('session', $sessionManager);
$twig->addGlobal('request', $requestHandler);
//$twig->addGlobal('base_url', $base_url);

// No return needed, objects are now available in the global scope of included files
// URL CONTROLLER?
