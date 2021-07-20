<?

IncludeModuleLangFile(__FILE__);
use \Bitrix\Main\ModuleManager;

class bx_logger extends CModule
{
    public $MODULE_ID = "bx.logger";
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $errors;

    public function __construct()
    {
        $this->MODULE_VERSION = "1.0.0";
        $this->MODULE_VERSION_DATE = "2021-07-19 21:47:13";
        $this->MODULE_NAME = "PSR-3 логер";
        $this->MODULE_DESCRIPTION = "PSR-3 логер";
    }

    public function DoInstall()
    {
        ModuleManager::RegisterModule($this->MODULE_ID);
        return true;
    }

    public function DoUninstall()
    {
        ModuleManager::UnRegisterModule($this->MODULE_ID);
        return true;
    }
}
