<?php

namespace Bx\Logger;

use Bitrix\Main\Diag\Debug;
use Psr\Log\AbstractLogger;
use Stringable;

class DebugLogger extends AbstractLogger
{
    /**
     * @var string
     */
    private string $fileName;

    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    public function log($level, string|Stringable $message, array $context = []): void
    {
        $isDumpMode = $context['DUMP'] === true;

        if ($isDumpMode) {
            Debug::dumpToFile($message, $this->fileName);
        } else {
            Debug::writeToFile($message, $this->fileName);
        }
    }
}
