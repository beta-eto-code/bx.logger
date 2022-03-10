<?php

namespace Bx\Logger;

class Utils
{
    /**
     * @param mixed $message
     * @param array $context
     * @return string
     */
    public static function interpolate($message, array $context = []): string
    {
        $replace = [];
        foreach ($context as $key => $val) {
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        return strtr($message, $replace);
    }
}
