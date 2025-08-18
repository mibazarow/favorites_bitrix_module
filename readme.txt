<button data-id="<?= $arResult['ID'] ?>" class="bazarow_add_favor" aria-label="Wishlist">
     <img src="<?= SITE_TEMPLATE_PATH ?>/img/heart_wish_dark.svg" alt="Wish">
</button>

<?
$APPLICATION->IncludeComponent("bazarow:favorites.add", "",
    array(),
);
?>

<?$APPLICATION->IncludeComponent("bazarow:favorites.list", "wish_page", Array(
    "CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
    "CACHE_GROUPS" => "Y",	// Учитывать права доступа
    "CACHE_TIME" => "0",	// Время кеширования (сек.)
    "CACHE_TYPE" => "A",	// Тип кеширования
    "COMPONENT_TEMPLATE" => ".default",
    "COMPOSITE_FRAME_MODE" => "A",
    "COMPOSITE_FRAME_TYPE" => "AUTO",
    "DATE_FORMAT" => "j M Y",	// Формат показа даты
    "DISPLAY_BOTTOM_PAGER" => "N",	// Выводить под списком
    "DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
    "FAVORITES_COUNT" => "99999999999",	// Количество избранных товаров на странице
    "FILTER_NAME" => "",	// Фильтр
    "NAV_TEMPLATE" => "round",	// Имя шаблона для постраничной навигации
    "SET_TITLE" => "Y",	// Устанавливать заголовок страницы
    "SORT_BY" => "DATE_INSERT",	// Поле для сортировки избранных товаров
    "SORT_ORDER" => "DESC",	// Направление для сортировки избранных товаров
    "IBLOCK_TYPE" => "1c_catalog",
    "IBLOCK_ID" => "6",
    "SHOW_DISCOUNT_PERCENT" => "Y",
    "SHOW_OLD_PRICE" => "Y",
    "ELEMENT_SORT_FIELD" => "sort",
    "ELEMENT_SORT_ORDER" => "asc",
    "ELEMENT_SORT_FIELD2" => "id",
    "ELEMENT_SORT_ORDER2" => "desc",
    "BASKET_URL" => "/personal/cart/",
    "PRICE_CODE" => array(
        0 => "Retail",
    ),
    "PROPERTY_CODE" => array(
        0 => "",
        1 => "",
    ),
    "OFFERS_FIELD_CODE" => array(
        0 => "",
        1 => "",
    ),
    "OFFERS_PROPERTY_CODE" => array(
        0 => "",
        1 => "",
    ),
    "OFFERS_LIMIT" => "5",
    "ADD_PROPERTIES_TO_BASKET" => "Y",
    "PRODUCT_PROPERTIES" => "",
    "OFFERS_CART_PROPERTIES" => ""
),
    false
);?>