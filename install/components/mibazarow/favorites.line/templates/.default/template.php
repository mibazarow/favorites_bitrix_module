<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
?>
<?/*button data-id="<?= $arResult['ID'] ?>" class="mibazarow_add_favor"></button*/?>
<div class="favor-list-wrap">
    <? echo (!empty($arResult['COUNT'])) ? $arResult['COUNT'] : '0' ?>
</div>