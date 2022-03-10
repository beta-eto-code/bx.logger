<?php

namespace Bx\Logger\Interfaces;

use Bitrix\Main\Result;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Throwable;

interface LoggerManagerInterface extends LoggerInterface, LoggerAwareInterface
{
    /**
     * @param Throwable $exception
     * @param bool $logTrace
     * @return mixed
     */
    public function logException(Throwable $exception, bool $logTrace = false);

    /**
     * @param Result $result
     * @param string $successMessage
     * @return mixed
     */
    public function logResult(Result $result, string $successMessage);
}
