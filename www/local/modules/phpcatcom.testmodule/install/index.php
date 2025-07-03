<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);

class phpcatcom_testmodule extends CModule
{
    var $MODULE_ID = "phpcatcom.testmodule";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;

    public function __construct()
    {
        $arModuleVersion = [];
        include(__DIR__ . "/version.php");

//        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
//        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

        $this->MODULE_NAME = Loc::getMessage("PHPCATCOM_TESTMODULE_MODULE_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("PHPCATCOM_TESTMODULE_MODULE_DESC");

        $this->PARTNER_NAME = Loc::getMessage("PHPCATCOM_TESTMODULE_PARTNER");
        $this->PARTNER_URI = Loc::getMessage("PHPCATCOM_TESTMODULE_PARTNER_URI");


//        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
//        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

//        $this->MODULE_NAME = GetMessage("SCOM_INSTALL_NAME");
//        $this->MODULE_DESCRIPTION = GetMessage("SCOM_INSTALL_DESCRIPTION");

//        $this->PARTNER_NAME = GetMessage("SPER_PARTNER");
//        $this->PARTNER_URI = GetMessage("PARTNER_URI");
    }


    public function DoInstall()
    {
        global $APPLICATION;
        $this->InstallDB();
        $this->InstallFiles();
        ModuleManager::registerModule($this->MODULE_ID);
        $APPLICATION->IncludeAdminFile(Loc::getMessage("PHPCATCOM_TESTMODULE_INSTALL_TITLE"), __DIR__ . "/step.php");
    }

    public function DoUninstall()
    {
        global $APPLICATION;
        $this->UnInstallDB();
        $this->UnInstallFiles();
        ModuleManager::unRegisterModule($this->MODULE_ID);
        $APPLICATION->IncludeAdminFile(Loc::getMessage("PHPCATCOM_TESTMODULE_UNINSTALL_TITLE"), __DIR__ . "/unstep.php");
    }

    public function InstallDB()
    {
        global $DB;
        $path = __DIR__ . "/db/install.sql";
        if (file_exists($path)) {
//            $sql = file_get_contents($path);
            $DB->RunSQLBatch($path);
        }
    }

    public function UnInstallDB()
    {
        global $DB;
        $path = __DIR__ . "/db/uninstall.sql";
        if (file_exists($path)) {
            $DB->RunSQLBatch($path);
        }
    }

    public function InstallFiles()
    {
        // Копирование файлов модуля в /bitrix/ (если нужно)
    }

    public function UnInstallFiles()
    {
        // Удаление файлов модуля из /bitrix/ (если нужно)
    }
}

?>