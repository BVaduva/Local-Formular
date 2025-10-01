<?php
#######################################################################
################# NOT USED CURRENTLY ##################################
#######################################################################


declare(strict_types=1);

namespace App\Utility;

use App\View\UIComponent;
use App\Model\QueryHandler;
use App\Controller\UserManager;
use App\Controller\AuthController;
use App\Utility\LogManager;

class ServiceContainer
{
    private $services = [];
    private LogManager $logManager;

    public function __construct(LogManager $logManager)
    {
        // Register services and their dependencies
        $this->services['twig'] = function () {
            $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../templates');
            return new \Twig\Environment($loader, [
                'cache' => __DIR__ . '/../var/cache/twig',
                'debug' => true,
            ]);
        };

        $this->services['sessionManager'] = function () {
            return new SessionManager($logManager);
        };

        $this->services['requestHandler'] = function () {
            return new RequestHandler();
        };

        $this->services['uiComponent'] = function () {
            return new UIComponent($this->get('twig'), $this->get('sessionManager'));
        };

        $this->services['queryHandler'] = function () {
            return new QueryHandler();
        };

        $this->services['userManager'] = function () {
            return new UserManager(
                $this->get('requestHandler'),
                $this->get('queryHandler'),
                $this->get('sessionManager')
            );
        };

        $this->services['authController'] = function () {
            return new AuthController(
                $this->get('userManager'),
                $this->get('sessionManager'),
                $this->get('queryHandler')
            );
        };
    }

    public function get(string $service)
    {
        if (!isset($this->services[$service])) {
            throw new \Exception("Service $service not found.");
        }

        // Return the result of the closure
        return $this->services[$service]();
    }
}
