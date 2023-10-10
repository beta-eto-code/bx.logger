<?php

namespace Bx\Logger;

use Bitrix\Main\Type\DateTime;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Stringable;

class SimpleTextLogger implements LoggerInterface
{
    use LoggerTrait;

    /**
     * @var string
     */
    private string $filePath;
    /**
     * @var string
     */
    private string $messageFormat;
    /**
     * @var string
     */
    private string $dateFormat;

    public function __construct(string $filePath, string $dateFormat = null, string $messageFormat = null)
    {
        $this->filePath = $filePath;
        $this->dateFormat = $dateFormat ?? 'd.m.Y H:i:s';
        $this->messageFormat = $messageFormat ?? '{date} {level}: {message}';
    }

    /**
     * @param mixed $level
     * @param mixed $message
     * @param array $context
     * @return void
     */
    public function log($level, string|Stringable $message, array $context = []): void
    {
        $fp = fopen($this->filePath, 'ab');
        if (!$fp) {
            return;
        }

        if (!flock($fp, LOCK_EX)) {
            return;
        }

        if (is_object($message) && method_exists($message, '__toString') || is_scalar($message)) {
            $message = (string) $message;
        } else {
            $message = print_r($message, true);
        }

        $message = Utils::interpolate($this->messageFormat, [
            'date' => (new DateTime())->format($this->dateFormat),
            'level' => $level,
            'message' => $message
        ]);

        fwrite($fp, $message . "\n");
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}
