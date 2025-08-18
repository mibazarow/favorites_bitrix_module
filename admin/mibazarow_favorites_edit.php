<?
use Bitrix\Main\Loader;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/mibazarow.favorites/admin/tools.php");

Loader::includeModule('mibazarow.favorites');
IncludeModuleLangFile(__FILE__);

global $DB;
$aTabs = array(
  array("DIV" => "edit1", "TAB" => GetMessage("mibazarow_FAVORITES_TAB_MAIN"), "ICON"=>"main_user_edit", "TITLE"=>GetMessage("mibazarow_FAVORITES_TAB_MAIN")),
  
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);

$ID = intval($ID);
$message = null;
$bVarsFromForm = false;


if(
    $REQUEST_METHOD == "POST" 
    &&
    ($save!="" || $apply!="") 
    &&
    check_bitrix_sessid()
)
{
  
  $arMap = \mibazarow\Favorites\FavoritesTable::getMap();
  $arFields = array();
  foreach($arMap as $key => $field){
  	if(isset($_REQUEST[$key]) && $field['editable']){
  		$arFields[$key] = $_REQUEST[$key];
  	}elseif($field['data_type'] == 'boolean' && $field['editable']){
  		$arFields[$key] = "N";
  	}
  }
 
  

  if($ID > 0)
  {
    $result = \mibazarow\Favorites\FavoritesTable::update($ID, $arFields);
  }
  else
  {
  	$arFields['CREATED'] = new \Bitrix\Main\Type\DateTime();
    $result = \mibazarow\Favorites\FavoritesTable::add($arFields);
    if($result->isSuccess()){
    	$ID = $result->getId();
    }
  }

  if($result->isSuccess())
  {
   
    if ($apply != "")
     
      LocalRedirect("/bitrix/admin/mibazarow_FAVORITES_edit.php?ID=".$ID."&mess=ok&lang=".LANG."&".$tabControl->ActiveTabParam());
    else
     LocalRedirect("/bitrix/admin/mibazarow_FAVORITES_list.php?lang=".LANG);
  }
  else
  {
    if($e = $result->getErrorMessages())
      $message = new CAdminMessage(GetMessage("mibazarow_FAVORITES_ERROR").implode("; ",$e));
    $bVarsFromForm = true;
  }
}


if($ID>0)
{
	$res = \mibazarow\Favorites\FavoritesTable::getById($ID);
  
  if(!$favorites_element = $res->fetch())
    $ID=0;
}



if($bVarsFromForm)
  $DB->InitTableVarsForEdit("b_list_favorites", "", "str_");


$APPLICATION->SetTitle(($ID>0? GetMessage("mibazarow_FAVORITES_EDIT_TITLE").$ID : GetMessage("mibazarow_FAVORITES_ADD_TITLE")));


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");


$aMenu = array(
  array(
    "TEXT"=>GetMessage("mibazarow_FAVORITES_LIST"),
    "TITLE"=>GetMessage("mibazarow_FAVORITES_LIST_TITLE"),
    "LINK"=>"mibazarow_favorites_list.php?lang=".LANG,
    "ICON"=>"btn_list",
  )
);

if($ID>0)
{
  $aMenu[] = array("SEPARATOR"=>"Y");
  $aMenu[] = array(
    "TEXT"=>GetMessage("mibazarow_FAVORITES_ADD"),
    "TITLE"=>GetMessage("mibazarow_FAVORITES_ADD"),
    "LINK"=>"mibazarow_favorites_edit.php?lang=".LANG,
    "ICON"=>"btn_new",
  );
  $aMenu[] = array(
    "TEXT"=>GetMessage("mibazarow_FAVORITES_DELETE"),
    "TITLE"=>GetMessage("mibazarow_FAVORITES_DELETE"),
    "LINK"=>"javascript:if(confirm('".GetMessage("mibazarow_FAVORITES_DELETE_CONF")."'))window.location='mibazarow_FAVORITES_list.php?ID=".$ID."&action=delete&lang=".LANG."&".bitrix_sessid_get()."';",
    "ICON"=>"btn_delete",
  );
  
}


$context = new CAdminContextMenu($aMenu);


$context->Show();
?>

<?

if($_REQUEST["mess"] == "ok" && $ID>0)
  CAdminMessage::ShowMessage(array("MESSAGE"=>GetMessage("mibazarow_FAVORITES_SAVED"), "TYPE"=>"OK"));

if($message)
  echo $message->Show();
elseif($favorites_element->LAST_ERROR!="")
  CAdminMessage::ShowMessage($favorites_element->LAST_ERROR);
?>


<form method="POST" action="<?echo $APPLICATION->GetCurPage()?>" enctype="multipart/form-data" name="favorites_edit_form">

<?echo bitrix_sessid_post();?>
<?
$tabControl->Begin();
CJSCore::Init(array('date'));
?>
<?
$tabControl->BeginNextTab();

$arMap = \mibazarow\Favorites\FavoritesTable::getMap();
foreach($arMap as $code => $field):
	if($field['hidden'] || $code == 'ID'){
		continue;
	}
	if($ID == 0 && !$field['editable']){
		continue;
	}
?>
  <tr>
    <td width="40%">
    	<?if($field['required']):?>
			<span class="adm-required-field"><?echo $field['title']?>:</span>
		<?else:?>
			<?echo $field['title']?>:
		<?endif;?>
	</td>
    <td width="60%">
		<?if($field['editable']):?>
			<?switch($field['data_type']){
				case 'datetime':
					echo CAdminCalendar::CalendarDate($code, $favorites_element[$code]->toString(), 19, true);
					break;
				case 'boolean':
					?><input type="checkbox" name="<?=$code?>" value="Y"<?if($favorites_element[$code] == "Y") echo " checked"?>/>	<?
					break;	
				case 'integer':
				case 'string':
					if($code == 'ELEMENT_ID'){
						\mibazarow\Favorites\mibazarowFavoritesTools::ShowElementField($code,$field,array($favorites_element[$code]));
					}elseif($code == 'USER_ID'){
						print \mibazarow\Favorites\mibazarowFavoritesTools::ShowUserField($code,$field,array("VALUE" => $favorites_element[$code]));
					}else{
						?><input type="text" name="<?=$code?>" value="<?=$favorites_element[$code]?>"/>	<?
					}
					break;
			}?>
				
			
			
		<?else:?>
			<?if(is_object($favorites_element[$code])):?>
				<?if(method_exists($favorites_element[$code],'toString')):?>
					<?=$favorites_element[$code]->toString();?>
				<?endif;?>
			<?else:?>
				<?=$favorites_element[$code]?>
			<?endif;?>
		<?endif;?>
		</td>
  </tr>
<?endforeach;?>
  
<?
$tabControl->Buttons(
  array(
    "disabled"=>false,
    "back_url"=>"rubric_admin.php?lang=".LANG,
    
  )
);
?>
<input type="hidden" name="lang" value="<?=LANG?>">
<?if($ID>0 && !$bCopy):?>
  <input type="hidden" name="ID" value="<?=$ID?>">
<?endif;?>
<?
$tabControl->End();
$tabControl->ShowWarnings("favorites_edit_form", $message);
?>


<?
echo BeginNote();?>

<span class="required">*</span><?echo GetMessage("REQUIRED_FIELDS")?>
<?echo EndNote();?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>