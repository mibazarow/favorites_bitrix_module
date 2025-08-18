<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("mibazarow_FAVORITES_ADD_DESC_LIST"),
	"DESCRIPTION" => GetMessage("mibazarow_FAVORITES_ADD_DESC_LIST_DESC"),
//	"ICON" => "/images/1c-imp.gif",
	"CACHE_PATH" => "Y",
	"SORT" => 120,
	"PATH" => array(
		"ID" => "mibazarow",
		"CHILD" => array(
			"ID" => "favorites",
			"NAME" => GetMessage("mibazarow_FAVORITES_ADD_DESC_GROUP"),
			"SORT" => 100,
		),
	),
);