<?
use Bitrix\Main\Loader;
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/mibazarow.favorites/admin/tools.php");

Loader::includeModule('mibazarow.favorites');

IncludeModuleLangFile(__FILE__);

$listTableId = "tbl_mibazarow_favorites_list";

$oSort = new CAdminSorting($listTableId, "ID", "asc");
$arOrder = (strtoupper($by) === "ID"? array($by => $order): array($by => $order, "ID" => "ASC"));

$adminList = new CAdminList($listTableId, $oSort);
function CheckFilter()
{
  global $arFilterFields, $adminList;
  foreach ($arFilterFields as $f) global $$f;
  return count($adminList->arFilterErrors)==0;
}

$FilterArr = Array(
  "find",
  "find_type",
  "find_id",
  "find_lid",
  "find_active",
  "find_visible",
  "find_auto",
  );
$arFilterFields = array(
	"find_created_from",
	"find_created_to",
	"find_user_id",
	"item_id_from",
	"item_id_to",
);
$adminList->InitFilter($arFilterFields);

if (CheckFilter())
{

  $arFilter = array();
	
	if (!empty($find_user_id))
		$arFilter["USER_ID"] = $find_user_id;
	if (!empty($find_created_from))
		$arFilter[">=CREATED"] = $find_created_from;
	if (!empty($find_created_to))
		$arFilter["<=CREATED"] = $find_created_to;
	if (!empty($find_id_from))
		$arFilter[">=ELEMENT_ID"] = $find_id_from;
	if (!empty($find_id_to))
		$arFilter["<=ELEMENT_ID"] = $find_id_to;

}


if($adminList->EditAction())
{
  foreach($FIELDS as $ID=>$arFields)
  {
    if(!$adminList->IsUpdated($ID))
      continue;

    $DB->StartTransaction();
    $ID = IntVal($ID);
    $res = \mibazarow\Favorites\FavoritesTable::getById($ID);
	if(!$arData = $res->fetch()){
		foreach($arFields as $key=>$value)
        	$arData[$key]=$value;
 		$result = \mibazarow\Favorites\FavoritesTable::update($ID, $arData);
 		
		if(!$result->isSuccess())
		{
			if($e = $result->getErrorMessages())
				$adminList->AddGroupError(GetMessage("mibazarow_FAVORITES_SAVE_ERROR")." ".$e, $ID);
			$DB->Rollback();
		}
	}
    else
    {
      $adminList->AddGroupError(GetMessage("mibazarow_FAVORITES_SAVE_ERROR")." ".GetMessage("mibazarow_FAVORITES_SAVE_ERROR"), $ID);
      $DB->Rollback();
    }
    $DB->Commit();
  }
}

if(($arID = $adminList->GroupAction()))
{
  if($_REQUEST['action_target']=='selected')
  {
    $rsData = \mibazarow\Favorites\FavoritesTable::getList(
		array(
			"filter" => $arFilter,
			'order' => array($by=>$order)
		)
	);
    while($arRes = $rsData->fetch())
      $arID[] = $arRes['ID'];
  }

  foreach($arID as $ID)
  {
    if(strlen($ID)<=0)
      continue;
       $ID = IntVal($ID);

    switch($_REQUEST['action'])
    {
    case "delete":
      @set_time_limit(0);
      $DB->StartTransaction();
      $result = \mibazarow\Favorites\FavoritesTable::delete($ID);
	  if(!$result->isSuccess())
	  {
	      $DB->Rollback();
          $adminList->AddGroupError(GetMessage("mibazarow_FAVORITES_DELETE_ERROR"), $ID);
	  }
      $DB->Commit();
      break;

    case "activate":
    case "deactivate":
      
      if(($rsData = \mibazarow\Favorites\FavoritesTable::getById($ID)) && ($arFields = $rsData->fetch()))
      {
        $arFields["ACTIVE"]=($_REQUEST['action']=="activate"?"Y":"N");
        $result = \mibazarow\Favorites\FavoritesTable::update($ID, $arFields);
        if(!$result->isSuccess())
        	if($e = $result->getErrorMessages())
          		$adminList->AddGroupError(GetMessage("mibazarow_FAVORITES_SAVE_ERROR").$e, $ID);
      }
      else
        $adminList->AddGroupError(GetMessage("mibazarow_FAVORITES_SAVE_ERROR")." ".GetMessage("mibazarow_FAVORITES_NO_ELEMENT"), $ID);
      break;
    }
  }
}


$myData = \mibazarow\Favorites\FavoritesTable::getList(
	array(
		'filter' => $arFilter,
		'order' => $arOrder
	)
);

$myData = new CAdminResult($myData, $listTableId);
$myData->NavStart();

$adminList->NavText($myData->GetNavPrint(GetMessage("mibazarow_FAVORITES_ADMIN_NAV")));

$cols = \mibazarow\Favorites\FavoritesTable::getMap();
$colHeaders = array();
foreach ($cols as $colId => $col)
{
	if($col['hidden']){
		continue;
	}
	$colHeaders[] = array(
		"id" => $colId,
		"content" => $col["title"],
		"sort" => $colId,
		"default" => true,
	);
}
$adminList->AddHeaders($colHeaders);

$visibleHeaderColumns = $adminList->GetVisibleHeaderColumns();
$arUsersCache = array();
$arElementCache = array();
while ($arRes = $myData->GetNext())
{
	$row =& $adminList->AddRow($arRes["ID"], $arRes);

	if (in_array("USER_ID", $visibleHeaderColumns) && intval($arRes["USER_ID"]) > 0)
	{
		if (!array_key_exists($arRes["USER_ID"], $arUsersCache))
		{
			$rsUser = CUser::GetByID($arRes["USER_ID"]);
			$arUsersCache[$arRes["USER_ID"]] = $rsUser->Fetch();
		}
		if ($arUser = $arUsersCache[$arRes["USER_ID"]])
			$row->AddViewField("USER_ID", '[<a href="user_edit.php?lang='.LANGUAGE_ID.'&ID='.$arRes["USER_ID"].'">'.$arRes["USER_ID"]."</a>]&nbsp;(".$arUser["LOGIN"].") ".$arUser["NAME"]." ".$arUser["LAST_NAME"]);
	}
	if (in_array("ELEMENT_ID", $visibleHeaderColumns) && intval($arRes["ELEMENT_ID"]) > 0)
	{
		if (!array_key_exists($arRes["ELEMENT_ID"], $arElementCache))
		{
			$res = \Bitrix\Iblock\ElementTable::getById($arRes["ELEMENT_ID"]);
			if($ar_res = $res->fetch()){
				$res_iblock = \Bitrix\Iblock\IblockTable::getById($ar_res["IBLOCK_ID"]);
				$ar_iblock_res = $res_iblock->fetch();
				$ar_res['IBLOCK_TYPE'] = $ar_iblock_res['IBLOCK_TYPE_ID'];
				$arElementCache[$arRes["ELEMENT_ID"]] = $ar_res;
			}
			
			
		}
		if ($arElement = $arElementCache[$arRes["ELEMENT_ID"]])
			$row->AddViewField("ELEMENT_ID", '[<a href="iblock_element_edit.php?IBLOCK_ID='.$arElement['IBLOCK_ID'].'&type='.$arElement['IBLOCK_TYPE'].'&lang='.LANGUAGE_ID.'&ID='.$arRes["ELEMENT_ID"].'">'.$arRes["ELEMENT_ID"]."</a>]&nbsp;(".$arElement["NAME"].") ");
	}
	if (in_array("NOTIFIED", $visibleHeaderColumns)){
		$row->AddViewField("NOTIFIED", $arRes['NOTIFIED'] == 'Y'?GetMessage("mibazarow_FAVORITES_YES"):GetMessage("mibazarow_FAVORITES_NO"));		
	}
	if (in_array("ACTIVE", $visibleHeaderColumns)){
		$row->AddViewField("ACTIVE", $arRes['ACTIVE'] == 'Y'?GetMessage("mibazarow_FAVORITES_YES"):GetMessage("mibazarow_FAVORITES_NO"));		
	}
	$el_edit_url = htmlspecialcharsbx(\mibazarow\Favorites\Tools\Favorites::GetAdminElementEditLink($arRes["ID"]));
	$arActions = array();
	$arActions[] = array(
		"ICON" => "edit",
		"TEXT" => GetMessage("mibazarow_FAVORITES_EDIT"),
		"ACTION" => $adminList->ActionRedirect($el_edit_url),
		"DEFAULT" => true,
	);
	$arActions[] = array(
		"ICON" => "delete",
		"TEXT" => GetMessage("mibazarow_FAVORITES_DELETE"),
		"ACTION" => "if(confirm('".GetMessageJS("mibazarow_FAVORITES_DEL_CONF")."')) ".$adminList->ActionDoGroup($arRes["ID"], "delete"),
	);
	$row->AddActions($arActions);
}

  
  
$adminList->AddFooter(
	array(
		array(
			"title" => GetMessage("MAIN_ADMIN_LIST_SELECTED"),
			"value" => $myData->SelectedRowsCount()
		),
		array(
			"counter" => true,
			"title" => GetMessage("MAIN_ADMIN_LIST_CHECKED"),
			"value" => "0"
		),
	)
);

$adminList->AddGroupActionTable(Array(
  "delete"=>GetMessage("MAIN_ADMIN_LIST_DELETE"),
  "activate"=>GetMessage("MAIN_ADMIN_LIST_ACTIVATE"),
  "deactivate"=>GetMessage("MAIN_ADMIN_LIST_DEACTIVATE"),
  ));


$adminList->CheckListMode();

$APPLICATION->SetTitle(GetMessage("mibazarow_FAVORITES_ADMIN_TITLE"));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

?>
<form name="filter_form" method="GET" action="<?echo $APPLICATION->GetCurPage()?>?">
<?
$oFilter = new CAdminFilter(
	$listTableId."_filter",
	array(
		GetMessage("mibazarow_FAVORITES_ADMIN_FILTER_USER_ID"),
		GetMessage("mibazarow_FAVORITES_ADMIN_FILTER_ITEM_ID"),
	)
);
$oFilter->Begin();
?>
<tr>
	<td><b><?echo GetMessage("mibazarow_FAVORITES_ADMIN_FILTER_CREATED")?>:</b></td>
	<td nowrap>
		<?echo CalendarPeriod("find_created_from", htmlspecialcharsex($find_created_from), "find_created_to", htmlspecialcharsex($find_created_to), "filter_form")?>
	</td>
</tr>
<tr>
	<td><?echo GetMessage("mibazarow_FAVORITES_ADMIN_FILTER_USER_ID")?>:</td>
	<td><?echo FindUserID("find_user_id", $find_user_id, "", "filter_form", "5", "", " ... ", "", "");?></td>
</tr>
	<tr>
		<td><?echo GetMessage("mibazarow_FAVORITES_ADMIN_FILTER_ITEM")?>:</td>

		<td>
			<input type="text" name="find_id_from" size="10" value="<?echo htmlspecialcharsex($find_id_from)?>">
			...
			<input type="text" name="find_id_to" size="10" value="<?echo htmlspecialcharsex($find_id_to)?>">
		</td>

	</tr>
<?
$oFilter->Buttons(
	array(
		"table_id" => $listTableId,
		"url" => $APPLICATION->GetCurPage(),
		"form" => "filter_form"
	)
);
$oFilter->End();
?>
</form>
<?
$adminList->DisplayList();
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>

