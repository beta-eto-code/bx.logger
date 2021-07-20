<?php

namespace Bx\Logger;

use Bitrix\Main\Application;
use Bitrix\Main\EventLog\Internal\EventLogTable;
use Exception;
use Psr\Log\AbstractLogger;

class JournalLogger extends AbstractLogger
{
    /**
     * @var string
     */
    private $defaultModuleId;
    /**
     * @var string
     */
    private $defaultAuditTypeId;

    public function __construct(string $moduleId = '', string $auditTypeId = '')
    {
        $this->defaultModuleId = $moduleId;
        $this->defaultAuditTypeId = $auditTypeId;
    }

    /**
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @throws Exception
     */
    public function log($level, $message, array $context = [])
    {
        global $USER;
        $appContext = Application::getInstance()->getContext();
        $server = $appContext->getServer();

        EventLogTable::add([
            'SEVERITY' => $level,
            'MODULE_ID' => $context['MODULE_ID'] ?? $this->defaultModuleId,
            'AUDIT_TYPE_ID' => $context['AUDIT_TYPE_ID'] ?? $this->defaultAuditTypeId,
            'ITEM_ID' => $context['ITEM_ID'] ?? '',
            'REMOTE_ADDR' => $context['REMOTE_ADDR'] ?? $server->getRemoteAddr(),
            'USER_AGENT' => $context['USER_AGENT'] ?? $server->getUserAgent(),
            'REQUEST_URI' => $context['REQUEST_URI'] ?? $server->getRequestUri(),
            'SITE_ID' => $context['SITE_ID'] ?? $appContext->getSite(),
            'USER_ID' => $context['USER_ID'] ?? $USER->GetID(),
            'GUEST_ID' => $context['GUEST_ID'] ?? '',
            'DESCRIPTION' => Utils::interpolate($message, $context),
        ]);
    }
}