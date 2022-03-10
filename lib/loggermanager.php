<?php

namespace Bx\Logger;

use Bitrix\Main\Result;
use Bx\Logger\Interfaces\LoggerManagerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Throwable;

class LoggerManager implements LoggerManagerInterface
{
    use LoggerTrait;

    /**
     * @var LoggerInterface[]
     */
    private $logger = [];

    public function __construct(LoggerInterface $defaultLogger)
    {
        $this->logger['default'] = $defaultLogger;
    }

    /**
     * @param LoggerInterface $logger
     * @param string $loggerType
     */
    public function setLogger(LoggerInterface $logger, string $loggerType = 'default')
    {
        $this->logger[$loggerType] = $logger;
    }

    /**
     * @return LoggerInterface
     */
    private function getDefaultLogger(): LoggerInterface
    {
        return $this->logger['default'];
    }

    /**
     * @param string $loggerType
     * @return LoggerInterface
     */
    private function getLoggerByType(string $loggerType): LoggerInterface
    {
        $logger = $this->logger[$loggerType] ?? null;
        if ($logger instanceof LoggerInterface) {
            return $logger;
        }

        return $this->getDefaultLogger();
    }

    /**
     * @param Throwable $exception
     * @param bool $logTrace
     * @return void
     */
    public function logException(Throwable $exception, bool $logTrace = false)
    {
        $message = $exception->getMessage();
        if ($logTrace) {
            $message .= "\n" . $exception->getTraceAsString();
        }

        $this->error(
            $message,
            [
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getPrevious(),
                'trace' => $exception->getTraceAsString(),
            ]
        );
    }

    /**
     * @param Result $result
     * @param string $successMessage
     */
    public function logResult(Result $result, string $successMessage)
    {
        if ($result->isSuccess()) {
            $this->info($successMessage, [
                'data' => $result->getData(),
            ]);

            return;
        }

        $this->error(implode(', ', $result->getErrorMessages()), [
            'data' => $result->getData(),
        ]);
    }

    /**
     * @param mixed $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = array())
    {
        $this->getLoggerByType($level)->log($level, $message, $context);
    }
}
