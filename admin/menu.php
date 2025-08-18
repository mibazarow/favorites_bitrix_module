<?php
use Bitrix\Main\Localization\Loc;

IncludeModuleLangFile(__FILE__);

if ($GLOBALS['APPLICATION']->GetGroupRight("main") < "R")
    return;

$MODULE_ID = basename(dirname(__FILE__));
$aMenu = array(
    //"parent_menu" => "global_menu_services",
    "parent_menu" => "global_menu_content",
    "section" => $MODULE_ID,
    "sort" => 999,
    "text" => GetMessage('mibazarow_FAVORITES_TITLE'),
    "title" => GetMessage('mibazarow_FAVORITES_TITLE'),
    "icon" => "",
    "page_icon" => "",
    "items_id" => $MODULE_ID . "_items",
    "more_url" => array(),
    // "items" => array()
    "items" => array(
        array(
            "text" => GetMessage("mibazarow_FAVORITES_LIST"),
            "url" => "mibazarow_favorites_list.php?lang=" . LANGUAGE_ID,
            "title" => GetMessage("mibazarow_FAVORITES_LIST"),
        ),
        array(
            "text" => GetMessage("mibazarow_FAVORITES_EDIT"),
            "url" => "mibazarow_favorites_edit.php?lang=" . LANGUAGE_ID,
            "title" => GetMessage("mibazarow_FAVORITES_EDIT"),
        )
    ),
);
return $aMenu;