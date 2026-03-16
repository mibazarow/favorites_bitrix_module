<?php
namespace mibazarow\Favorites;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Agents
{
	/**
	 * Агент: удаляет записи избранного, у которых элемент инфоблока больше не существует.
	 * Запускается раз в сутки.
	 *
	 * @return string  Возвращает вызов самого себя для повторного запуска
	 */
	public static function cleanDeletedElements(): string
	{
		if (!\Bitrix\Main\Loader::includeModule('mibazarow.favorites')) {
			return '';
		}
		if (!\Bitrix\Main\Loader::includeModule('iblock')) {
			return '';
		}

		// Получаем все уникальные ELEMENT_ID из таблицы избранного
		$dbFavorites = FavoritesTable::getList([
			'select' => ['ELEMENT_ID'],
			'group'  => ['ELEMENT_ID'],
		]);

		$elementIds = [];
		while ($row = $dbFavorites->fetch()) {
			$elementIds[] = (int)$row['ELEMENT_ID'];
		}

		if (empty($elementIds)) {
			return '\mibazarow\Favorites\Agents::cleanDeletedElements();';
		}

		// Проверяем какие из этих ID реально существуют в инфоблоках
		$existingIds = [];
		$dbElements = \CIBlockElement::GetList(
			[],
			['ID' => $elementIds],
			false,
			false,
			['ID']
		);
		while ($arEl = $dbElements->Fetch()) {
			$existingIds[] = (int)$arEl['ID'];
		}

		// Находим ID, которых больше нет
		$deletedIds = array_diff($elementIds, $existingIds);

		if (empty($deletedIds)) {
			return '\mibazarow\Favorites\Agents::cleanDeletedElements();';
		}

		// Удаляем все записи избранного по несуществующим элементам
		foreach ($deletedIds as $elementId) {
			$dbToDelete = FavoritesTable::getList([
				'select' => ['ID'],
				'filter' => ['ELEMENT_ID' => $elementId],
			]);
			while ($row = $dbToDelete->fetch()) {
				FavoritesTable::delete($row['ID']);
			}
		}

		return '\mibazarow\Favorites\Agents::cleanDeletedElements();';
	}
}

