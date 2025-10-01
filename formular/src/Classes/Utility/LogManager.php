<?php
namespace App\Utility;

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

class LogManager
{
    private Logger $logger;

    public function __construct(string $logFile)
    {
        $this->logger = new Logger('my_logger');
        $this->logger->pushHandler(new StreamHandler($logFile, Level::Debug));
        $this->logger->pushHandler(new FirePHPHandler());
    }

    public function logDebug(string $message, array $context = []): void
    {
        $this->logger->debug($message, $context);
    }

    public function logInfo(string $message, array $context = []): void
    {
        $this->logger->info($message, $context);
    }

    public function logNotice(string $message, array $context = []): void
    {
        $this->logger->notice($message, $context);
    }

    public function logWarning(string $message, array $context = []): void
    {
        $this->logger->warning($message, $context);
    }

    public function logError(string $message, array $context = []): void
    {
        $this->logger->error($message, $context);
    }

    public function logCritical(string $message, array $context = []): void
    {
        $this->logger->critical($message, $context);
    }

    public function logAlert(string $message, array $context = []): void
    {
        $this->logger->alert($message, $context);
    }

    public function logEmergency(string $message, array $context = []): void
    {
        $this->logger->emergency($message, $context);
    }
}
