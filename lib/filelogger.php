<?php

namespace Bx\Logger;

use Psr\Log\AbstractLogger;

class FileLogger extends AbstractLogger
{
    /**
     * @param mixed $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = array())
    {
        AddMessage2Log(
            "{$level}: ".Utils::interpolate($message, $context),
            $context['MODULE_ID'] ?? '',
            $context['TRACE_DEPTH'] ?? 6,
            $context['SHOW_ARGS'] ?? false
        );
    }
}