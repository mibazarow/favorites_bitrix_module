<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->createFrame()->begin("");
// TODO массив в $arResult["FAVORITES"
$arItems = array();
foreach ($arResult["FAVORITES"] as $arItem) {
    array_push($arItems, $arItem['ELEMENT']['ID']);
}
$GLOBALS['arrFilterWish'] = array('ID' => $arItems);
if (count($arItems) > 1) {
    $APPLICATION->IncludeComponent(
        "bitrix:catalog.section",
        "",
        array(
            "IBLOCK_TYPE" => "catalog",
            "IBLOCK_ID" => "3",
            "SECTION_ID" => "",
            "SECTION_CODE" => "",
            "SECTION_USER_FIELDS" => array(
                0 => "",
                1 => "",
            ),
            "ELEMENT_SORT_FIELD" => "RAND",
            "ELEMENT_SORT_ORDER" => "asc",
            "ELEMENT_SORT_FIELD2" => "id",
            "ELEMENT_SORT_ORDER2" => "desc",
            "FILTER_NAME" => "arrFilterWish",
            "INCLUDE_SUBSECTIONS" => "Y",
            "SHOW_ALL_WO_SECTION" => "Y",
            "HIDE_NOT_AVAILABLE" => "Y",
            "PAGE_ELEMENT_COUNT" => "28",
            "LINE_ELEMENT_COUNT" => "3",
            "PROPERTY_CODE" => array(
                0 => "",
                1 => "",
            ),
            "OFFERS_LIMIT" => "5",
            "SECTION_URL" => "",
            "DETAIL_URL" => "/catalog/#SECTION_CODE_PATH#/#ELEMENT_CODE#.php",
            "SECTION_ID_VARIABLE" => "SECTION_ID",
            "AJAX_MODE" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "N",
            "AJAX_OPTION_HISTORY" => "N",
            "AJAX_OPTION_ADDITIONAL" => "",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "36000000",
            "CACHE_NOTES" => "",
            "CACHE_GROUPS" => "Y",
            "SET_TITLE" => "N",
            "SET_BROWSER_TITLE" => "N",
            "BROWSER_TITLE" => "-",
            "SET_META_KEYWORDS" => "N",
            "META_KEYWORDS" => "-",
            "SET_META_DESCRIPTION" => "N",
            "META_DESCRIPTION" => "-",
            "ADD_SECTIONS_CHAIN" => "N",
            "SET_STATUS_404" => "Y",
            "CACHE_FILTER" => "N",
            "ACTION_VARIABLE" => "action",
            "PRODUCT_ID_VARIABLE" => "id",
            "PRICE_CODE" => array(
                0 => "BASE",
            ),
            "USE_PRICE_COUNT" => "N",
            "SHOW_PRICE_COUNT" => "1",
            "PRICE_VAT_INCLUDE" => "Y",
            "CONVERT_CURRENCY" => "N",
            "BASKET_URL" => "/personal/cart/",
            "USE_PRODUCT_QUANTITY" => "N",
            "PRODUCT_QUANTITY_VARIABLE" => "quantity",
            "ADD_PROPERTIES_TO_BASKET" => "N",
            "PRODUCT_PROPS_VARIABLE" => "prop",
            "PARTIAL_PRODUCT_PROPERTIES" => "Y",
            "PRODUCT_PROPERTIES" => array(),
            "DISPLAY_COMPARE" => "N",
            "PAGER_TEMPLATE" => "round",
            "DISPLAY_TOP_PAGER" => "N",
            "DISPLAY_BOTTOM_PAGER" => "N",
            "PAGER_TITLE" => "Товары",
            "PAGER_SHOW_ALWAYS" => "N",
            "PAGER_DESC_NUMBERING" => "N",
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
            "PAGER_SHOW_ALL" => "N",
            "COMPONENT_TEMPLATE" => "section",
            "CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[]}",
            "HIDE_NOT_AVAILABLE_OFFERS" => "Y",
            "OFFERS_SORT_FIELD" => "SCALED_PRICE_2",
            "OFFERS_SORT_ORDER" => "asc",
            "OFFERS_SORT_FIELD2" => "SCALED_PRICE_2",
            "OFFERS_SORT_ORDER2" => "asc",
            "OFFERS_FIELD_CODE" => array(
                0 => "",
                1 => "",
            ),
            "BACKGROUND_IMAGE" => "-",
            "TEMPLATE_THEME" => "blue",
            "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
            "ENLARGE_PRODUCT" => "STRICT",
            "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
            "SHOW_SLIDER" => "N",
            "PRODUCT_DISPLAY_MODE" => "Y",
            "ADD_PICT_PROP" => "-",
            "LABEL_PROP" => array(),
            "OFFER_ADD_PICT_PROP" => "-",
            "PRODUCT_SUBSCRIPTION" => "N",
            "SHOW_DISCOUNT_PERCENT" => "N",
            "SHOW_OLD_PRICE" => "N",
            "SHOW_MAX_QUANTITY" => "N",
            "SHOW_CLOSE_POPUP" => "Y",
            "MESS_BTN_BUY" => "Купить",
            "MESS_BTN_ADD_TO_BASKET" => "В корзину",
            "MESS_BTN_SUBSCRIBE" => "Подписаться",
            "MESS_BTN_DETAIL" => "Подробнее",
            "MESS_NOT_AVAILABLE" => "Нет в наличии",
            "RCM_TYPE" => "personal",
            "RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
            "SHOW_FROM_SECTION" => "N",
            "SEF_MODE" => "N",
            "SET_LAST_MODIFIED" => "N",
            "USE_MAIN_ELEMENT_SECTION" => "N",
            "ADD_TO_BASKET_ACTION" => "ADD",
            "USE_ENHANCED_ECOMMERCE" => "N",
            "PAGER_BASE_LINK_ENABLE" => "N",
            "LAZY_LOAD" => "Y",
            "MESS_BTN_LAZY_LOAD" => "Показать ещё",
            "LOAD_ON_SCROLL" => "N",
            "SHOW_404" => "Y",
            "FILE_404" => "/404.php",
            "COMPATIBLE_MODE" => "N",
            "DISABLE_INIT_JS_IN_COMPONENT" => "N",
            "PROPERTY_CODE_MOBILE" => array(),
            "OFFERS_PROPERTY_CODE" => array(
                0 => "",
                1 => "",
            ),
            "OFFER_TREE_PROPS" => array(),
            "OFFERS_CART_PROPERTIES" => array()
        ),
        false
    );
} else { ?>
    Пока в избранном пусто
<? } ?>