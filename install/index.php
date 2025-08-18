<?
IncludeModuleLangFile(__FILE__);
if (class_exists("mibazarow_favorites"))
	return;

Class mibazarow_favorites extends CModule
{
	const MODULE_ID = 'mibazarow.favorites';
	var $MODULE_ID = 'mibazarow.favorites';
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $strError = '';

	function __construct()
	{
		$arModuleVersion = array();
		include(dirname(__FILE__)."/version.php");
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = GetMessage("mibazarow.favorites_MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("mibazarow.favorites_MODULE_DESC");

		$this->PARTNER_NAME = GetMessage("mibazarow.favorites_PARTNER_NAME");
		$this->PARTNER_URI = GetMessage("mibazarow.favorites_PARTNER_URI");
	}

	function InstallDB(array $arParams = array()): bool
	{
		global $DB;
		RegisterModule(self::MODULE_ID);
		RegisterModuleDependences(
			'main',
			'OnBuildGlobalMenu',
			self::MODULE_ID,
			'BazFavorites',
			'OnBuildGlobalMenu'
		);
		$DB->RunSQLBatch(dirname(__FILE__)."/sql/install.sql");
		return true;
	}

	function UnInstallDB(array $arParams = array()): bool
	{
		global $DB;
		UnRegisterModule(self::MODULE_ID);
		UnRegisterModuleDependences(
			'main',
			'OnBuildGlobalMenu',
			self::MODULE_ID,
			'BazFavorites',
			'OnBuildGlobalMenu'
		);

		$DB->RunSQLBatch(dirname(__FILE__)."/sql/uninstall.sql");
		
		return true;
	}

	function InstallEvents(): bool
	{
		global $DB;
		//include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".self::MODULE_ID."/install/events.php");
		return true;
	}

	function UnInstallEvents(): bool
	{
		$et = new \CEventType;
		$eventM = new \CEventMessage;
		$et->Delete("mibazarow_FAVORITES_NOTIFICATION");
		$dbEvent = \CEventMessage::GetList(
			$b="ID",
			$order="ASC",
			Array(
				"EVENT_NAME" => "mibazarow_FAVORITES_NOTIFICATION"
			)
		);
		while($arEvent = $dbEvent->Fetch())
		{
			$eventM->Delete($arEvent["ID"]);
		}
		return true;
	}

	function InstallFiles(array $arParams = array()): bool
	{
		CopyDirFiles(
			dirname(__FILE__)."/admin", 
			$_SERVER["DOCUMENT_ROOT"]."/bitrix/admin", 
			true
		);
		if (is_dir(
			$p = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/install/components')
			)
		{
			if ($dir = opendir($p))
			{
				while (false !== $item = readdir($dir))
				{
					if ($item == '..' || $item == '.')
						continue;
					CopyDirFiles($p.'/'.$item, $_SERVER['DOCUMENT_ROOT'].'/local/components/'.$item, $ReWrite = True, $Recursive = True);
				}
				closedir($dir);
			}
		}
		return true;
	}

	function UnInstallFiles(): bool
	{
		DeleteDirFiles(dirname(__FILE__)."/admin", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin");
		if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/install/components'))
		{
			if ($dir = opendir($p))
			{
				while (false !== $item = readdir($dir))
				{
					if ($item == '..' || $item == '.' || !is_dir($p0 = $p.'/'.$item))
						continue;

					$dir0 = opendir($p0);
					while (false !== $item0 = readdir($dir0))
					{
						if ($item0 == '..' || $item0 == '.')
							continue;
						DeleteDirFilesEx('/local/components/'.$item.'/'.$item0);
					}
					closedir($dir0);
				}
				closedir($dir);
			}
		}
		return true;
	}

	function DoInstall(): void
	{
		global $APPLICATION;
		$this->InstallFiles();
		$this->InstallDB();
	}

	function DoUninstall(): void
	{
		global $APPLICATION;
		$this->UnInstallDB();
		$this->UnInstallFiles();
	}
}
?>
