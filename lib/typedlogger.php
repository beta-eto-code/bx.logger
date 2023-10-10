<?php

namespace Bx\Logger;

use Bx\Logger\Interfaces\TypedLoggerInterface;
use Psr\Log\LoggerInterface;
use Stringable;

class TypedLogger implements TypedLoggerInterface
{
    private LoggerInterface $originalLogger;
    private string $type;

    public function __construct(LoggerInterface $logger, string $type)
    {
        $this->originalLogger = $logger;
        $this->type = $type;
    }

    /**
     * @inheritDoc
     */
    public function emergency(Stringable|string $message, array $context = []): void
    {
        $this->originalLogger->emergency($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function alert(Stringable|string $message, array $context = []): void
    {
        $this->originalLogger->alert($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function critical(Stringable|string $message, array $context = []): void
    {
        $this->originalLogger->critical($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function error(Stringable|string $message, array $context = []): void
    {
        $this->originalLogger->error($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function warning(Stringable|string $message, array $context = []): void
    {
        $this->originalLogger->warning($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function notice(Stringable|string $message, array $context = []): void
    {
        $this->originalLogger->notice($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function info(Stringable|string $message, array $context = []): void
    {
        $this->originalLogger->info($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function debug(Stringable|string $message, array $context = []): void
    {
        $this->originalLogger->debug($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function log($level, Stringable|string $message, array $context = []): void
    {
        $this->originalLogger->log($level, $message, $context);
    }

    public function getType(): string
    {
        return $this->type;
    }
}
