<?php

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\Loader,
	Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class phpcatcom_testmodule extends CModule
{
	var $MODULE_ID = "phpcatcom.testmodule";
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;

	var $errors;

	function __construct()
	{
		$arModuleVersion = array();

		include(__DIR__.'/version.php');

		if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion))
		{
			$this->MODULE_VERSION = $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		}

		$this->MODULE_NAME = Loc::getMessage("PHPCATCOM_TESTMODULE_INSTALL_NAME");
		$this->MODULE_DESCRIPTION = Loc::getMessage("PHPCATCOM_TESTMODULE_INSTALL_DESCRIPTION");
	}

	function InstallDB()
	{
		global $DB, $APPLICATION;
		$connection = \Bitrix\Main\Application::getConnection();
		$this->errors = false;

		if(!$DB->TableExists('b_PHPCATCOM_TESTMODULE_type'))
		{
			$this->errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/PHPCATCOM_TESTMODULE/install/db/' . $connection->getType() . '/install.sql');
		}

		if($this->errors !== false)
		{
			$APPLICATION->ThrowException(implode("", $this->errors));
			return false;
		}

		ModuleManager::registerModule("PHPCATCOM_TESTMODULE");
		$eventManager = \Bitrix\Main\EventManager::getInstance();
		$eventManager->registerEventHandlerCompatible("main", "OnGroupDelete", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULE", "OnGroupDelete");
		$eventManager->registerEventHandlerCompatible("main", "OnBeforeLangDelete", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULE", "OnBeforeLangDelete");
		$eventManager->registerEventHandlerCompatible("main", "OnLangDelete", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULE", "OnLangDelete");
		$eventManager->registerEventHandlerCompatible("main", "OnUserTypeRightsCheck", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULESection", "UserTypeRightsCheck");
		$eventManager->registerEventHandlerCompatible("search", "OnReindex", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULE", "OnSearchReindex");
		$eventManager->registerEventHandlerCompatible("search", "OnSearchGetURL", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULE", "OnSearchGetURL");
		$eventManager->registerEventHandlerCompatible("main", "OnEventLogGetAuditTypes", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULE", "GetAuditTypes");
		$eventManager->registerEventHandlerCompatible("main", "OnEventLogGetAuditHandlers", "PHPCATCOM_TESTMODULE", "CEventPHPCATCOM_TESTMODULE", "MakePHPCATCOM_TESTMODULEObject");
		$eventManager->registerEventHandlerCompatible("main", "OnGetRatingContentOwner", "PHPCATCOM_TESTMODULE", "CRatingsComponentsPHPCATCOM_TESTMODULE", "OnGetRatingContentOwner", 200);
		$eventManager->registerEventHandlerCompatible("main", "OnTaskOperationsChanged", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULERightsStorage", "OnTaskOperationsChanged");
		$eventManager->registerEventHandlerCompatible("main", "OnGroupDelete", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULERightsStorage", "OnGroupDelete");
		$eventManager->registerEventHandlerCompatible("main", "OnUserDelete", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULERightsStorage", "OnUserDelete");
		$eventManager->registerEventHandlerCompatible("perfmon", "OnGetTableSchema", "PHPCATCOM_TESTMODULE", "PHPCATCOM_TESTMODULE", "OnGetTableSchema");
		$eventManager->registerEventHandlerCompatible("sender", "OnConnectorList", "PHPCATCOM_TESTMODULE", "\\Bitrix\\PHPCATCOM_TESTMODULE\\SenderEventHandler", "onConnectorListPHPCATCOM_TESTMODULE");

		$eventManager->registerEventHandlerCompatible("PHPCATCOM_TESTMODULE", "OnPHPCATCOM_TESTMODULEPropertyBuildList", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULEPropertyDate", "GetUserTypeDescription", 10);
		$eventManager->registerEventHandlerCompatible("PHPCATCOM_TESTMODULE", "OnPHPCATCOM_TESTMODULEPropertyBuildList", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULEPropertyDateTime", "GetUserTypeDescription", 20);
		$eventManager->registerEventHandlerCompatible("PHPCATCOM_TESTMODULE", "OnPHPCATCOM_TESTMODULEPropertyBuildList", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULEPropertyXmlID", "GetUserTypeDescription", 30);
		$eventManager->registerEventHandlerCompatible("PHPCATCOM_TESTMODULE", "OnPHPCATCOM_TESTMODULEPropertyBuildList", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULEPropertyFileMan", "GetUserTypeDescription", 40);
		$eventManager->registerEventHandlerCompatible("PHPCATCOM_TESTMODULE", "OnPHPCATCOM_TESTMODULEPropertyBuildList", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULEPropertyHTML", "GetUserTypeDescription", 50);
		$eventManager->registerEventHandlerCompatible("PHPCATCOM_TESTMODULE", "OnPHPCATCOM_TESTMODULEPropertyBuildList", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULEPropertyElementList", "GetUserTypeDescription", 60);
		$eventManager->registerEventHandlerCompatible("PHPCATCOM_TESTMODULE", "OnPHPCATCOM_TESTMODULEPropertyBuildList", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULEPropertySequence", "GetUserTypeDescription", 70);
		$eventManager->registerEventHandlerCompatible("PHPCATCOM_TESTMODULE", "OnPHPCATCOM_TESTMODULEPropertyBuildList", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULEPropertyElementAutoComplete", "GetUserTypeDescription", 80);
		$eventManager->registerEventHandlerCompatible("PHPCATCOM_TESTMODULE", "OnPHPCATCOM_TESTMODULEPropertyBuildList", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULEPropertySKU", "GetUserTypeDescription", 90);
		$eventManager->registerEventHandlerCompatible("PHPCATCOM_TESTMODULE", "OnPHPCATCOM_TESTMODULEPropertyBuildList", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULEPropertySectionAutoComplete", "GetUserTypeDescription", 100);

		$eventManager->registerEventHandler("main", "onVirtualClassBuildList", "PHPCATCOM_TESTMODULE", \Bitrix\PHPCATCOM_TESTMODULE\PHPCATCOM_TESTMODULETable::class, "compileAllEntities");
		//$eventManager->registerEventHandler("landing", "OnBuildSourceList", "PHPCATCOM_TESTMODULE", "\\Bitrix\\PHPCATCOM_TESTMODULE\\LandingSource\\Element", "onBuildSourceListHandler");
		unset($eventManager);

		$this->InstallTasks();

		return true;
	}

	function UnInstallDB($arParams = array())
	{
		global $DB, $APPLICATION;
		$connection = \Bitrix\Main\Application::getConnection();
		$this->errors = false;
		$arSQLErrors = array();

		if(Loader::includeModule("search"))
			CSearch::DeleteIndex("PHPCATCOM_TESTMODULE");
		if(!Loader::includeModule("PHPCATCOM_TESTMODULE"))
			return false;

		$arSql = $arErr = array();
		if(!array_key_exists("savedata", $arParams) || ($arParams["savedata"] != "Y"))
		{
			$rsPHPCATCOM_TESTMODULE = CPHPCATCOM_TESTMODULE::GetList(array("ID"=>"ASC"), array(), false);
			while ($arPHPCATCOM_TESTMODULE = $rsPHPCATCOM_TESTMODULE->Fetch())
			{
				if($arPHPCATCOM_TESTMODULE["VERSION"] == 2)
				{
					$arSql[] = "DROP TABLE if exists b_PHPCATCOM_TESTMODULE_element_prop_s" . $arPHPCATCOM_TESTMODULE["ID"];
					$arSql[] = "DROP TABLE if exists b_PHPCATCOM_TESTMODULE_element_prop_m" . $arPHPCATCOM_TESTMODULE["ID"];
				}
				$arSql[] = "DROP TABLE if exists " . \Bitrix\PHPCATCOM_TESTMODULE\FullIndex\FullText::getTableName($arPHPCATCOM_TESTMODULE["ID"]);
				$GLOBALS["USER_FIELD_MANAGER"]->OnEntityDelete("PHPCATCOM_TESTMODULE_".$arPHPCATCOM_TESTMODULE["ID"]."._SECTION");
			}

			foreach($arSql as $strSql)
			{
				if(!$DB->Query($strSql, true))
					$arSQLErrors[] = "<hr><pre>Query:\n".$strSql."\n\nError:\n<span style=\"color: red;\">".$DB->db_Error."</span></pre>";
			}

			$db_res = $DB->Query("SELECT ID FROM b_file WHERE MODULE_ID = 'PHPCATCOM_TESTMODULE'");
			while($arRes = $db_res->Fetch())
				CFile::Delete($arRes["ID"]);

			$this->errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/PHPCATCOM_TESTMODULE/install/db/".$connection->getType()."/uninstall.sql");

			$this->UnInstallTasks();
		}

		if(is_array($this->errors))
			$arSQLErrors = array_merge($arSQLErrors, $this->errors);

		if(!empty($arSQLErrors))
		{
			$this->errors = $arSQLErrors;
			$APPLICATION->ThrowException(implode("", $arSQLErrors));
			return false;
		}

		$eventManager = \Bitrix\Main\EventManager::getInstance();
		$eventManager->unRegisterEventHandler("main", "OnGroupDelete", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULE", "OnGroupDelete");
		$eventManager->unRegisterEventHandler("main", "OnBeforeLangDelete", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULE", "OnBeforeLangDelete");
		$eventManager->unRegisterEventHandler("main", "OnLangDelete", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULE", "OnLangDelete");
		$eventManager->unRegisterEventHandler("main", "OnUserTypeRightsCheck", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULESection", "UserTypeRightsCheck");
		$eventManager->unRegisterEventHandler("search", "OnReindex", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULE", "OnSearchReindex");
		$eventManager->unRegisterEventHandler("search", "OnSearchGetURL", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULE", "OnSearchGetURL");
		$eventManager->unRegisterEventHandler("main", "OnEventLogGetAuditTypes", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULE", "GetAuditTypes");
		$eventManager->unRegisterEventHandler("main", "OnEventLogGetAuditHandlers", "PHPCATCOM_TESTMODULE", "CEventPHPCATCOM_TESTMODULE", "MakePHPCATCOM_TESTMODULEObject");
		$eventManager->unRegisterEventHandler("main", "OnGetRatingContentOwner", "PHPCATCOM_TESTMODULE", "CRatingsComponentsPHPCATCOM_TESTMODULE", "OnGetRatingContentOwner");
		$eventManager->unRegisterEventHandler("main", "OnTaskOperationsChanged", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULERightsStorage", "OnTaskOperationsChanged");
		$eventManager->unRegisterEventHandler("main", "OnGroupDelete", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULERightsStorage", "OnGroupDelete");
		$eventManager->unRegisterEventHandler("main", "OnUserDelete", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULERightsStorage", "OnUserDelete");
		$eventManager->unRegisterEventHandler("perfmon", "OnGetTableSchema", "PHPCATCOM_TESTMODULE", "PHPCATCOM_TESTMODULE", "OnGetTableSchema");
		$eventManager->unRegisterEventHandler("sender", "OnConnectorList", "PHPCATCOM_TESTMODULE", "\\Bitrix\\PHPCATCOM_TESTMODULE\\SenderEventHandler", "onConnectorListPHPCATCOM_TESTMODULE");

		$eventManager->unRegisterEventHandler("PHPCATCOM_TESTMODULE", "OnPHPCATCOM_TESTMODULEPropertyBuildList", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULEPropertyDate", "GetUserTypeDescription");
		$eventManager->unRegisterEventHandler("PHPCATCOM_TESTMODULE", "OnPHPCATCOM_TESTMODULEPropertyBuildList", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULEPropertyDateTime", "GetUserTypeDescription");
		$eventManager->unRegisterEventHandler("PHPCATCOM_TESTMODULE", "OnPHPCATCOM_TESTMODULEPropertyBuildList", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULEPropertyXmlID", "GetUserTypeDescription");
		$eventManager->unRegisterEventHandler("PHPCATCOM_TESTMODULE", "OnPHPCATCOM_TESTMODULEPropertyBuildList", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULEPropertyFileMan", "GetUserTypeDescription");
		$eventManager->unRegisterEventHandler("PHPCATCOM_TESTMODULE", "OnPHPCATCOM_TESTMODULEPropertyBuildList", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULEPropertyHTML", "GetUserTypeDescription");
		$eventManager->unRegisterEventHandler("PHPCATCOM_TESTMODULE", "OnPHPCATCOM_TESTMODULEPropertyBuildList", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULEPropertyElementList", "GetUserTypeDescription");
		$eventManager->unRegisterEventHandler("PHPCATCOM_TESTMODULE", "OnPHPCATCOM_TESTMODULEPropertyBuildList", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULEPropertySequence", "GetUserTypeDescription");
		$eventManager->unRegisterEventHandler("PHPCATCOM_TESTMODULE", "OnPHPCATCOM_TESTMODULEPropertyBuildList", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULEPropertyElementAutoComplete", "GetUserTypeDescription");
		$eventManager->unRegisterEventHandler("PHPCATCOM_TESTMODULE", "OnPHPCATCOM_TESTMODULEPropertyBuildList", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULEPropertySKU", "GetUserTypeDescription");
		$eventManager->unRegisterEventHandler("PHPCATCOM_TESTMODULE", "OnPHPCATCOM_TESTMODULEPropertyBuildList", "PHPCATCOM_TESTMODULE", "CPHPCATCOM_TESTMODULEPropertySectionAutoComplete", "GetUserTypeDescription");

		$eventManager->unregisterEventHandler("main", "onVirtualClassBuildList", "PHPCATCOM_TESTMODULE", \Bitrix\PHPCATCOM_TESTMODULE\PHPCATCOM_TESTMODULETable::class, "compileAllEntities");
		//$eventManager->unRegisterEventHandler("landing", "OnBuildSourceList", "PHPCATCOM_TESTMODULE", "\\Bitrix\\PHPCATCOM_TESTMODULE\\LandingSource\\Element", "onBuildSourceListHandler");
		unset($eventManager);

		ModuleManager::unRegisterModule("PHPCATCOM_TESTMODULE");

		return true;
	}

	function InstallEvents()
	{
		return true;
	}

	function UnInstallEvents()
	{
		return true;
	}

	function InstallFiles()
	{
		CopyDirFiles($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/PHPCATCOM_TESTMODULE/install/admin', $_SERVER['DOCUMENT_ROOT']."/bitrix/admin");
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/PHPCATCOM_TESTMODULE/install/js", $_SERVER["DOCUMENT_ROOT"]."/bitrix/js/", true, true);
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/PHPCATCOM_TESTMODULE/install/images", $_SERVER["DOCUMENT_ROOT"]."/bitrix/images/PHPCATCOM_TESTMODULE", true, true);
		if(file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/PHPCATCOM_TESTMODULE/install/public/rss.php"))
			@copy($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/PHPCATCOM_TESTMODULE/install/public/rss.php", $_SERVER["DOCUMENT_ROOT"]."/bitrix/rss.php");
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/PHPCATCOM_TESTMODULE/install/themes", $_SERVER["DOCUMENT_ROOT"]."/bitrix/themes", true, true);
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/PHPCATCOM_TESTMODULE/install/components", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/PHPCATCOM_TESTMODULE/install/gadgets", $_SERVER["DOCUMENT_ROOT"]."/bitrix/gadgets", true, true);
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/PHPCATCOM_TESTMODULE/install/panel", $_SERVER["DOCUMENT_ROOT"]."/bitrix/panel", true, true);
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/PHPCATCOM_TESTMODULE/install/tools", $_SERVER["DOCUMENT_ROOT"]."/bitrix/tools", true, true);
		return true;
	}

	function UnInstallFiles()
	{
		DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/PHPCATCOM_TESTMODULE/install/admin", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin");
		DeleteDirFilesEx("/bitrix/images/PHPCATCOM_TESTMODULE/");//images
		DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/PHPCATCOM_TESTMODULE/install/public/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/");
		DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/PHPCATCOM_TESTMODULE/install/themes/.default/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/themes/.default");//css
		DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/PHPCATCOM_TESTMODULE/install/panel/PHPCATCOM_TESTMODULE/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/panel/PHPCATCOM_TESTMODULE/");//css sku
		DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/PHPCATCOM_TESTMODULE/install/tools/PHPCATCOM_TESTMODULE/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/tools/PHPCATCOM_TESTMODULE/");
		DeleteDirFilesEx("/bitrix/themes/.default/icons/PHPCATCOM_TESTMODULE/");//icons
		DeleteDirFilesEx("/bitrix/js/PHPCATCOM_TESTMODULE/");//javascript
		return true;
	}


	function DoInstall()
	{
		global $APPLICATION, $step, $obModule;
		$step = intval($step);
		if($step<2)
			$APPLICATION->IncludeAdminFile(Loc::getMessage("PHPCATCOM_TESTMODULE_INSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/PHPCATCOM_TESTMODULE/install/step1.php");
		elseif($step==2)
		{
			if($this->InstallDB())
			{
				$this->InstallFiles();
			}
			$obModule = $this;
			$APPLICATION->IncludeAdminFile(Loc::getMessage("PHPCATCOM_TESTMODULE_INSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/PHPCATCOM_TESTMODULE/install/step2.php");
		}
	}

	function DoUninstall()
	{
		global $APPLICATION, $step, $obModule;
		$step = intval($step);
		if($step<2)
			$APPLICATION->IncludeAdminFile(Loc::getMessage("PHPCATCOM_TESTMODULE_INSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/PHPCATCOM_TESTMODULE/install/unstep1.php");
		elseif($step==2)
		{
			$this->UnInstallDB(array(
				"savedata" => $_REQUEST["savedata"],
			));
			$GLOBALS["CACHE_MANAGER"]->CleanAll();
			$this->UnInstallFiles();
			$obModule = $this;
			$APPLICATION->IncludeAdminFile(Loc::getMessage("PHPCATCOM_TESTMODULE_INSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/PHPCATCOM_TESTMODULE/install/unstep2.php");
		}
	}

	function GetModuleTasks()
	{
		return array(
			'PHPCATCOM_TESTMODULE_deny' => array(
				'LETTER' => 'D',
				'BINDING' => 'PHPCATCOM_TESTMODULE',
				'OPERATIONS' => array(
				)
			),
			'PHPCATCOM_TESTMODULE_read' => array(
				'LETTER' => 'R',
				'BINDING' => 'PHPCATCOM_TESTMODULE',
				'OPERATIONS' => array(
					'section_read',
					'element_read'
				)
			),
			'PHPCATCOM_TESTMODULE_element_add' => array(
				'LETTER' => 'E',
				'BINDING' => 'PHPCATCOM_TESTMODULE',
				'OPERATIONS' => array(
					'section_element_bind'
				)
			),
			'PHPCATCOM_TESTMODULE_admin_read' => array(
				'LETTER' => 'S',
				'BINDING' => 'PHPCATCOM_TESTMODULE',
				'OPERATIONS' => array(
					'PHPCATCOM_TESTMODULE_admin_display',
					'section_read',
					'element_read'
				)
			),
			'PHPCATCOM_TESTMODULE_admin_add' => array(
				'LETTER' => 'T',
				'BINDING' => 'PHPCATCOM_TESTMODULE',
				'OPERATIONS' => array(
					'PHPCATCOM_TESTMODULE_admin_display',
					'section_read',
					'section_element_bind',
					'element_read',
				)
			),
			'PHPCATCOM_TESTMODULE_limited_edit' => array(
				'LETTER' => 'U',
				'BINDING' => 'PHPCATCOM_TESTMODULE',
				'OPERATIONS' => array(
					'PHPCATCOM_TESTMODULE_admin_display',
					'section_read',
					'section_element_bind',
					'element_read',
					'element_edit',
					'element_edit_price',
					'element_delete',
					'element_bizproc_start'
				)
			),
			'PHPCATCOM_TESTMODULE_full_edit' => array(
				'LETTER' => 'W',
				'BINDING' => 'PHPCATCOM_TESTMODULE',
				'OPERATIONS' => array(
					'PHPCATCOM_TESTMODULE_admin_display',
					'section_read',
					'section_edit',
					'section_delete',
					'section_element_bind',
					'section_section_bind',
					'element_read',
					'element_edit',
					'element_edit_price',
					'element_delete',
					'element_edit_any_wf_status',
					'element_bizproc_start'
				)
			),
			'PHPCATCOM_TESTMODULE_full' => array(
				'LETTER' => 'X',
				'BINDING' => 'PHPCATCOM_TESTMODULE',
				'OPERATIONS' => array(
					'PHPCATCOM_TESTMODULE_admin_display',
					'PHPCATCOM_TESTMODULE_edit',
					'PHPCATCOM_TESTMODULE_delete',
					'PHPCATCOM_TESTMODULE_rights_edit',
					'PHPCATCOM_TESTMODULE_export',
					'section_read',
					'section_edit',
					'section_delete',
					'section_element_bind',
					'section_section_bind',
					'section_rights_edit',
					'element_read',
					'element_edit',
					'element_edit_price',
					'element_delete',
					'element_edit_any_wf_status',
					'element_bizproc_start',
					'element_rights_edit'
				)
			)
		);
	}

	public static function OnGetTableSchema()
	{
		return array(
			"PHPCATCOM_TESTMODULE" => array(
				"b_PHPCATCOM_TESTMODULE_type" => array(
					"ID" => array(
						"b_PHPCATCOM_TESTMODULE_type_lang" => "PHPCATCOM_TESTMODULE_TYPE_ID",
						"b_PHPCATCOM_TESTMODULE" => "PHPCATCOM_TESTMODULE_TYPE_ID",
					)
				),
				"b_PHPCATCOM_TESTMODULE" => array(
					"ID" => array(
						"b_PHPCATCOM_TESTMODULE_site" => "PHPCATCOM_TESTMODULE_ID",
						"b_PHPCATCOM_TESTMODULE_messages" => "PHPCATCOM_TESTMODULE_ID",
						"b_PHPCATCOM_TESTMODULE_fields" => "PHPCATCOM_TESTMODULE_ID",
						"b_PHPCATCOM_TESTMODULE_property" => "PHPCATCOM_TESTMODULE_ID",
						"b_PHPCATCOM_TESTMODULE_property^" => "LINK_PHPCATCOM_TESTMODULE_ID",
						"b_PHPCATCOM_TESTMODULE_section" => "PHPCATCOM_TESTMODULE_ID",
						"b_PHPCATCOM_TESTMODULE_element" => "PHPCATCOM_TESTMODULE_ID",
						"b_PHPCATCOM_TESTMODULE_group" => "PHPCATCOM_TESTMODULE_ID",
						"b_PHPCATCOM_TESTMODULE_right" => "PHPCATCOM_TESTMODULE_ID",
						"b_PHPCATCOM_TESTMODULE_section_right" => "PHPCATCOM_TESTMODULE_ID",
						"b_PHPCATCOM_TESTMODULE_element_right" => "PHPCATCOM_TESTMODULE_ID",
						"b_PHPCATCOM_TESTMODULE_rss" => "PHPCATCOM_TESTMODULE_ID",
						"b_PHPCATCOM_TESTMODULE_sequence" => "PHPCATCOM_TESTMODULE_ID",
						"b_PHPCATCOM_TESTMODULE_offers_tmp" => "PRODUCT_PHPCATCOM_TESTMODULE_ID",
						"b_PHPCATCOM_TESTMODULE_offers_tmp^" => "OFFERS_PHPCATCOM_TESTMODULE_ID",
						"b_PHPCATCOM_TESTMODULE_right^" => "ENTITY_ID",
						"b_PHPCATCOM_TESTMODULE_section_property" => "PHPCATCOM_TESTMODULE_ID",
						"b_PHPCATCOM_TESTMODULE_PHPCATCOM_TESTMODULE_iprop" => "PHPCATCOM_TESTMODULE_ID",
						"b_PHPCATCOM_TESTMODULE_section_iprop" => "PHPCATCOM_TESTMODULE_ID",
						"b_PHPCATCOM_TESTMODULE_element_iprop" => "PHPCATCOM_TESTMODULE_ID",
					)
				),
				"b_PHPCATCOM_TESTMODULE_section" => array(
					"ID" => array(
						"b_PHPCATCOM_TESTMODULE_section" => "PHPCATCOM_TESTMODULE_SECTION_ID",
						"b_PHPCATCOM_TESTMODULE_element" => "PHPCATCOM_TESTMODULE_SECTION_ID",
						"b_PHPCATCOM_TESTMODULE_right" => "ENTITY_ID",
						"b_PHPCATCOM_TESTMODULE_section_right" => "SECTION_ID",
						"b_PHPCATCOM_TESTMODULE_element_right" => "SECTION_ID",
						"b_PHPCATCOM_TESTMODULE_section_element" => "PHPCATCOM_TESTMODULE_SECTION_ID",
						"b_PHPCATCOM_TESTMODULE_section_property" => "SECTION_ID",
						"b_PHPCATCOM_TESTMODULE_section_iprop" => "SECTION_ID",
						"b_PHPCATCOM_TESTMODULE_element_iprop" => "SECTION_ID",
					)
				),
				"b_PHPCATCOM_TESTMODULE_element" => array(
					"ID" => array(
						"b_PHPCATCOM_TESTMODULE_element" => "WF_PARENT_ELEMENT_ID",
						"b_PHPCATCOM_TESTMODULE_element_property" => "PHPCATCOM_TESTMODULE_ELEMENT_ID",
						"b_PHPCATCOM_TESTMODULE_right" => "ENTITY_ID",
						"b_PHPCATCOM_TESTMODULE_element_right" => "ELEMENT_ID",
						"b_PHPCATCOM_TESTMODULE_section_element" => "PHPCATCOM_TESTMODULE_ELEMENT_ID",
						"b_PHPCATCOM_TESTMODULE_element_iprop" => "ELEMENT_ID",
					)
				),
				"b_PHPCATCOM_TESTMODULE_property" => array(
					"ID" => array(
						"b_PHPCATCOM_TESTMODULE_element_property" => "PHPCATCOM_TESTMODULE_PROPERTY_ID",
						"b_PHPCATCOM_TESTMODULE_property_enum" => "PROPERTY_ID",
						"b_PHPCATCOM_TESTMODULE_section_element" => "ADDITIONAL_PROPERTY_ID",
						"b_PHPCATCOM_TESTMODULE_section_property" => "PROPERTY_ID",
					)
				),
				"b_PHPCATCOM_TESTMODULE_right" => array(
					"ID" => array(
						"b_PHPCATCOM_TESTMODULE_section_right" => "RIGHT_ID",
						"b_PHPCATCOM_TESTMODULE_element_right" => "RIGHT_ID",
					)
				),
				"b_PHPCATCOM_TESTMODULE_iproperty" => array(
					"ID" => array(
						"b_PHPCATCOM_TESTMODULE_PHPCATCOM_TESTMODULE_iprop" => "IPROP_ID",
						"b_PHPCATCOM_TESTMODULE_section_iprop" => "IPROP_ID",
						"b_PHPCATCOM_TESTMODULE_element_iprop" => "IPROP_ID",
					)
				),
			),
			"main" => array(
				"b_file" => array(
					"ID" => array(
						"b_PHPCATCOM_TESTMODULE" => "PICTURE",
						"b_PHPCATCOM_TESTMODULE_section" => "PICTURE",
						"b_PHPCATCOM_TESTMODULE_section^" => "DETAIL_PICTURE",
						"b_PHPCATCOM_TESTMODULE_element" => "PREVIEW_PICTURE",
						"b_PHPCATCOM_TESTMODULE_element^" => "DETAIL_PICTURE",
					)
				),
				"b_lang" => array(
					"LID" => array(
						"b_PHPCATCOM_TESTMODULE" => "LID",
						"b_PHPCATCOM_TESTMODULE_site" => "SITE_ID",
					)
				),
				"b_user" => array(
					"ID" => array(
						"b_PHPCATCOM_TESTMODULE_section" => "MODIFIED_BY",
						"b_PHPCATCOM_TESTMODULE_section^" => "CREATED_BY",
						"b_PHPCATCOM_TESTMODULE_element" => "MODIFIED_BY",
						"b_PHPCATCOM_TESTMODULE_element^" => "CREATED_BY",
						"b_PHPCATCOM_TESTMODULE_element^^" => "WF_LOCKED_BY",
						"b_PHPCATCOM_TESTMODULE_element_lock" => "LOCKED_BY",
					)
				),
				"b_group" => array(
					"ID" => array(
						"b_PHPCATCOM_TESTMODULE_group" => "GROUP_ID",
					)
				),
				"b_task" => array(
					"ID" => array(
						"b_PHPCATCOM_TESTMODULE_right" => "TASK_ID",
						"b_task_operation" => "TASK_ID",
					)
				),
				"b_operation" => array(
					"ID" => array(
						"b_task_operation" => "OPERATION_ID",
					)
				),
			),
			"socialnetwork" => array(
				"b_sonet_group" => array(
					"ID" => array(
						"b_PHPCATCOM_TESTMODULE" => "SOCNET_GROUP_ID",
						"b_PHPCATCOM_TESTMODULE_section" => "SOCNET_GROUP_ID",
					)
				),
			),
		);
	}
}
