<?php

namespace Bx\Logger;

use Bx\Logger\Interfaces\TypedLoggerInterface;
use Psr\Log\LoggerInterface;

class TypedLoggerFactory
{
    /**
     * @param LoggerInterface $logger
     * @param string $type
     * @return TypedLoggerInterface
     */
    public static function createTypedLogger(LoggerInterface $logger, string $type): TypedLoggerInterface
    {
        return new TypedLogger($logger, $type);
    }
}
