<?php

namespace Bx\Logger;

use Bitrix\Main\Type\DateTime;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

class SimpleTextLogger implements LoggerInterface
{
    use LoggerTrait;

    /**
     * @var string
     */
    private $filePath;
    /**
     * @var string
     */
    private $messageFormat;
    /**
     * @var string
     */
    private $dateFormat;

    public function __construct(string $filePath, string $dateFormat = null, string $messageFormat = null)
    {
        $this->filePath = $filePath;
        $this->dateFormat = $dateFormat ?? 'd.m.Y H:i:s';
        $this->messageFormat = $messageFormat ?? '{date} {level}: {message}';
    }

    public function log($level, $message, array $context = array())
    {
        $fp = fopen($this->filePath, 'ab');
        if (!$fp) {
            return;
        }

        if (!flock($fp, LOCK_EX)) {
            return;
        }

        if (is_object($message) && method_exists($message, '__toString') || is_scalar($message)) {
            $message = (string)$message;
        } else {
            $message = print_r($message, true);
        }

        $message = Utils::interpolate($this->messageFormat, [
            'date' => (new DateTime())->format($this->dateFormat),
            'level' => $level,
            'message' => $message
        ]);

        fwrite($fp, $message."\n");
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}