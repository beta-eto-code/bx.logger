<?php

namespace Bx\Logger;

use Bitrix\Main\Result;
use Bx\Logger\Interfaces\LoggerManagerInterface;
use Bx\Logger\Interfaces\TypedLoggerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Stringable;
use Throwable;

class LoggerManager implements LoggerManagerInterface
{
    use LoggerTrait;

    private const DEFAULT_LOG = 'default';

    /**
     * @var LoggerInterface[]
     */
    private array $logger = [];

    public function __construct(LoggerInterface $defaultLogger)
    {
        $this->logger[$this::DEFAULT_LOG] = $defaultLogger;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $type = $logger instanceof TypedLoggerInterface ? $logger->getType() : $this::DEFAULT_LOG;
        $this->logger[$type ?: $this::DEFAULT_LOG] = $logger;
    }

    /**
     * @return LoggerInterface
     */
    private function getDefaultLogger(): LoggerInterface
    {
        return $this->logger[$this::DEFAULT_LOG];
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
     * @param $level
     * @param string|Stringable $message
     * @param array $context
     * @return void
     */
    public function log($level, string|Stringable $message, array $context = []): void
    {
        $this->getLoggerByType($level)->log($level, $message, $context);
    }
}
