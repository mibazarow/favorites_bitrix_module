<?php
namespace mibazarow\Favorites\Tools;

class Favorites
{
	public static function ShowElementField(string $name, $value, array $arUserField, bool $bVarsFromForm): string
	{
		$result = "";
		if(!is_array($value))
			$value = array($value);
		
		foreach($value as $i => $val)
		{
			if($val > 0)
			{
				$rsElement = \CIBlockElement::GetList(array(), array("ID" => $val), false, false, array("ID", "NAME", "IBLOCK_ID"));
				if($arElement = $rsElement->Fetch())
				{
					$result .= "<div style='margin:2px 0px;'>";
					$result .= "<input name=\"".$name."[".$i."]\" id=\"".$name."_".$i."\" value=\"".$val."\" size=\"8\" type=\"text\">";
					$result .= "<input type=\"button\" value=\"...\" onclick=\"jsUtils.OpenWindow('/bitrix/admin/iblock_element_search.php?lang=".LANGUAGE_ID."&IBLOCK_ID=".$arElement["IBLOCK_ID"]."&n=".$name."&k=".$i."', 900, 700);\">";
					$result .= "&nbsp;<span id=\"sp_".$name."_".$i."\">".$arElement["NAME"]."</span>";
					$result .= "&nbsp;<input type=\"button\" value=\"x\" onclick=\"document.getElementById('".$name."_".$i."').value='';document.getElementById('sp_".$name."_".$i."').innerHTML='';\" title=\"".GetMessage("USER_TYPE_PROP_DELETE")."\">";
					$result .= "</div>";
				}
			}
			else
			{
				$result .= "<div style='margin:2px 0px;'>";
				$result .= "<input name=\"".$name."[".$i."]\" id=\"".$name."_".$i."\" value=\"\" size=\"8\" type=\"text\">";
				$result .= "<input type=\"button\" value=\"...\" onclick=\"jsUtils.OpenWindow('/bitrix/admin/iblock_element_search.php?lang=".LANGUAGE_ID."&n=".$name."&k=".$i."', 900, 700);\">";
				$result .= "&nbsp;<span id=\"sp_".$name."_".$i."\"></span>";
				$result .= "&nbsp;<input type=\"button\" value=\"x\" onclick=\"document.getElementById('".$name."_".$i."').value='';document.getElementById('sp_".$name."_".$i."').innerHTML='';\" title=\"".GetMessage("USER_TYPE_PROP_DELETE")."\">";
				$result .= "</div>";
			}
		}
		
		$result .= "<div style='margin:2px 0px;'>";
		$result .= "<input type=\"button\" value=\"".GetMessage("USER_TYPE_PROP_ADD")."\" onclick=\"";
		$result .= "var d = document.createElement('DIV');";
		$result .= "d.style.margin='2px 0px';";
		$result .= "var n = document.getElementsByName('".$name."[]').length;";
		$result .= "d.innerHTML='<input name=\\\"".$name."['+n+']\\\" id=\\\"".$name."_'+n+'\\\" value=\\\"\\\" size=\\\"8\\\" type=\\\"text\\\"><input type=\\\"button\\\" value=\\\"...\\\" onclick=\\\"jsUtils.OpenWindow(\\\"/bitrix/admin/iblock_element_search.php?lang=".LANGUAGE_ID."&n=".$name."&k='+n+', 900, 700);\\\">&nbsp;<span id=\\\"sp_".$name."_'+n+'\\\">&nbsp;</span>&nbsp;<input type=\\\"button\\\" value=\\\"x\\\" onclick=\\\"document.getElementById(\\\"".$name."_'+n+').value=\\\"\\\";document.getElementById(\\\"sp_".$name."_'+n+').innerHTML=\\\"\\\";\\\" title=\\\"".GetMessage("USER_TYPE_PROP_DELETE")."\\\">'+'';</div>';";
		$result .= "this.parentNode.insertBefore(d, this.parentNode.lastChild);";
		$result .= "\">";
		$result .= "</div>";
		
		return $result;
	}
	
	public static function ShowUserField(string $name, $value, array $arUserField, bool $bVarsFromForm): string
	{
		return \mibazarow\Favorites\Property\UserID::GetPropertyFieldHtml(
			array(),
			array("VALUE" => $value),
			array(
				"VALUE" => $name,
				"FORM_NAME" => "form_".$arUserField["FIELD_NAME"]
			)
		);
	}
	
	public static function GetAdminElementEditLink(int $ELEMENT_ID, int $IBLOCK_ID): string
	{
		return "/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=".$IBLOCK_ID."&type=".\CIBlock::GetArrayByID($IBLOCK_ID, "IBLOCK_TYPE_ID")."&ID=".$ELEMENT_ID."&lang=".LANGUAGE_ID."&find_section_section=-1";
	}
}