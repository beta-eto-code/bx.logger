<?php

namespace Bx\Logger;

use Psr\Log\AbstractLogger;
use Stringable;

class FileLogger extends AbstractLogger
{
    /**
     * @param $level
     * @param string|Stringable $message
     * @param array $context
     * @return void
     */
    public function log($level, string|Stringable $message, array $context = []): void
    {
        AddMessage2Log(
            "{$level}: " . Utils::interpolate($message, $context),
            $context['MODULE_ID'] ?? '',
            $context['TRACE_DEPTH'] ?? 6,
            $context['SHOW_ARGS'] ?? false
        );
    }
}
