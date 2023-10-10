<?php

namespace Bx\Logger\Interfaces;

use Psr\Log\LoggerInterface;

interface TypedLoggerInterface extends LoggerInterface
{
    public function getType(): string;
}
