<?php

namespace Bx\Logger;

use Bitrix\Main\Diag\Debug;
use Psr\Log\AbstractLogger;

class DebugLogger extends AbstractLogger
{
    /**
     * @var string
     */
    private $fileName;

    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    public function log($level, $message, array $context = array())
    {
        $isDumpMode = $context['DUMP'] === true;

        if ($isDumpMode) {
            Debug::dumpToFile($message, $this->fileName);
        } else {
            Debug::writeToFile($message, $this->fileName);
        }
    }
}