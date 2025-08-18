# Модуль "Избранные товары" для 1C-Bitrix

## Описание

Модуль **mibazarow.favorites** предназначен для реализации функциональности избранных товаров на сайте, построенном на CMS 1C-Bitrix. Модуль позволяет пользователям добавлять товары в избранное, просматривать список избранных товаров и управлять ими.

**Версия:** 1.0.1  
**Дата релиза:** 2024-06-08  
**Автор:** mibazarow  
**Сайт:** http://mibazarow.ru

## Основные возможности

- ✅ Добавление товаров в избранное для авторизованных и неавторизованных пользователей
- ✅ Отображение списка избранных товаров с пагинацией
- ✅ Отображение количества избранных товаров (счетчик)
- ✅ Административный интерфейс для управления избранными товарами
- ✅ Поддержка AJAX-запросов
- ✅ Работа с cookie для неавторизованных пользователей
- ✅ Современная архитектура с использованием пространств имен
- ✅ Строгая типизация PHP 8+

## Системные требования

- 1C-Bitrix версии 17.0 и выше
- PHP 8.0 и выше
- Модуль "Информационные блоки" (iblock)
- MySQL 5.7 и выше

## Установка

### Автоматическая установка

1. Скопируйте папку модуля в директорию `/local/modules/mibazarow.favorites/`
2. Перейдите в административную панель: **Настройки → Управление структурой → Управление модулями**
3. Найдите модуль "Избранные товары" и нажмите "Установить"

### Ручная установка

```bash
# Копирование файлов модуля
cp -r mibazarow.favorites /path/to/bitrix/local/modules/

# Выполнение SQL-скрипта установки
mysql -u username -p database_name < /path/to/bitrix/local/modules/mibazarow.favorites/install/sql/install.sql
```

## Структура базы данных

Модуль создает таблицу `mibazarow_favorites` со следующей структурой:

```sql
CREATE TABLE mibazarow_favorites (
    ID int NOT NULL auto_increment,
    ACTIVE char(1) not null DEFAULT 'Y',
    DATE_INSERT datetime NOT NULL,
    DATE_UPDATE datetime NULL,
    USER_ID int NULL,
    COOKIE_USER_ID varchar(64) NULL,
    ELEMENT_ID int NOT NULL,
    primary key (ID)
);
```

### Описание полей:

- **ID** - Уникальный идентификатор записи
- **ACTIVE** - Флаг активности записи (Y/N)
- **DATE_INSERT** - Дата добавления в избранное
- **DATE_UPDATE** - Дата последнего обновления
- **USER_ID** - ID авторизованного пользователя
- **COOKIE_USER_ID** - ID неавторизованного пользователя (cookie)
- **ELEMENT_ID** - ID элемента информационного блока

## Компоненты модуля

### 1. mibazarow:favorites.add

**Назначение:** Добавление товара в избранное

**Параметры:**
- `IBLOCK_ID` - ID информационного блока
- `ELEMENT_ID` - ID элемента для добавления
- `AJAX_MODE` - Режим AJAX (Y/N)

**Пример использования:**
```php
<?$APPLICATION->IncludeComponent(
    "mibazarow:favorites.add",
    "",
    array(
        "IBLOCK_ID" => 1,
        "ELEMENT_ID" => $arResult["ID"],
        "AJAX_MODE" => "Y"
    )
);?>
```

### 2. mibazarow:favorites.list

**Назначение:** Отображение списка избранных товаров

**Основные параметры:**
- `IBLOCK_ID` - ID информационного блока
- `FAVORITES_COUNT` - Количество элементов на странице
- `SORT_BY` - Поле для сортировки (ID, DATE_INSERT)
- `SORT_ORDER` - Порядок сортировки (ASC, DESC)
- `AJAX_MODE` - Режим AJAX (Y/N)

**Пример использования:**
```php
<?$APPLICATION->IncludeComponent(
    "mibazarow:favorites.list",
    "",
    array(
        "IBLOCK_ID" => 1,
        "FAVORITES_COUNT" => 10,
        "SORT_BY" => "DATE_INSERT",
        "SORT_ORDER" => "DESC",
        "AJAX_MODE" => "Y"
    )
);?>
```

### 3. mibazarow:favorites.line

**Назначение:** Отображение счетчика избранных товаров

**Параметры:**
- `IBLOCK_ID` - ID информационного блока
- `AJAX_MODE` - Режим AJAX (Y/N)

**Пример использования:**
```php
<?$APPLICATION->IncludeComponent(
    "mibazarow:favorites.line",
    "",
    array(
        "IBLOCK_ID" => 1,
        "AJAX_MODE" => "Y"
    )
);?>
```

## Административный интерфейс

Модуль добавляет в административную панель следующие разделы:

### Меню "Избранное пользователей"

Доступно через: **Главное меню → Избранное пользователей**

#### Список избранных записей
- Просмотр всех записей избранного
- Фильтрация по пользователю, товару, дате создания
- Массовые операции (удаление)
- Пагинация

#### Редактирование записи
- Изменение параметров записи избранного
- Выбор пользователя и товара
- Управление активностью записи

## API модуля

### Основные классы

#### mibazarow\Favorites\Tools\Favorites

Основной класс для работы с избранным:

```php
use mibazarow\Favorites\Tools\Favorites;

// Отображение поля выбора элемента
Favorites::ShowElementField($name, $value, $arProperty, $form);

// Отображение поля выбора пользователя  
Favorites::ShowUserField($name, $value, $arProperty, $form);

// Получение ссылки на редактирование элемента
Favorites::GetAdminElementEditLink($IBLOCK_ID, $ID);
```

#### mibazarow\Favorites\Property\UserID

Класс для работы с пользовательским типом свойства:

```php
use mibazarow\Favorites\Property\UserID;

// Получение описания типа свойства
UserID::GetUserTypeDescription();

// Отображение в административном списке
UserID::GetAdminListViewHTML($arProperty, $value, $strHTMLControlName);

// Отображение поля для редактирования
UserID::GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName);
```

### Работа с ORM

```php
use mibazarow\Favorites\FavoritesTable;

// Добавление в избранное
$result = FavoritesTable::add([
    'USER_ID' => $USER->GetID(),
    'ELEMENT_ID' => $elementId,
    'DATE_INSERT' => new \Bitrix\Main\Type\DateTime()
]);

// Получение списка избранного пользователя
$favorites = FavoritesTable::getList([
    'filter' => ['USER_ID' => $USER->GetID()],
    'order' => ['DATE_INSERT' => 'DESC']
]);

// Удаление из избранного
FavoritesTable::delete($id);
```

## Примеры интеграции

### Кнопка "Добавить в избранное" в карточке товара

```php
<div class="product-favorite">
    <?$APPLICATION->IncludeComponent(
        "mibazarow:favorites.add",
        "button",
        array(
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "ELEMENT_ID" => $arResult["ID"],
            "AJAX_MODE" => "Y",
            "CACHE_TYPE" => "N"
        )
    );?>
</div>
```

### Страница "Мое избранное"

```php
<?$APPLICATION->SetTitle("Избранные товары");?>

<div class="favorites-page">
    <div class="favorites-counter">
        <?$APPLICATION->IncludeComponent(
            "mibazarow:favorites.line",
            "",
            array(
                "IBLOCK_ID" => 1,
                "AJAX_MODE" => "Y"
            )
        );?>
    </div>
    
    <div class="favorites-list">
        <?$APPLICATION->IncludeComponent(
            "mibazarow:favorites.list",
            "grid",
            array(
                "IBLOCK_ID" => 1,
                "FAVORITES_COUNT" => 12,
                "SORT_BY" => "DATE_INSERT",
                "SORT_ORDER" => "DESC",
                "AJAX_MODE" => "Y",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => 3600
            )
        );?>
    </div>
</div>
```

### Счетчик в шапке сайта

```php
<div class="header-favorites">
    <a href="/favorites/">
        <i class="icon-heart"></i>
        <?$APPLICATION->IncludeComponent(
            "mibazarow:favorites.line",
            "counter",
            array(
                "IBLOCK_ID" => 1,
                "AJAX_MODE" => "Y"
            )
        );?>
    </a>
</div>
```

## Настройка и кастомизация

### Создание собственных шаблонов компонентов

1. Скопируйте шаблон из `/local/components/mibazarow/[component_name]/templates/.default/`
2. Создайте новую папку с именем вашего шаблона
3. Отредактируйте файлы `template.php`, `style.css`, `script.js`

### Расширение функциональности

Для добавления собственной логики создайте класс-наследник:

```php
namespace MyCompany\Favorites;

use mibazarow\Favorites\Tools\Favorites as BaseFavorites;

class CustomFavorites extends BaseFavorites 
{
    // Ваша дополнительная логика
}
```

## Обновление модуля

### С версии 1.0.0 до 1.0.1

1. Сделайте резервную копию базы данных
2. Скопируйте новые файлы модуля
3. Выполните обновление через административную панель

## Устранение неполадок

### Частые проблемы

**Проблема:** Компоненты не отображаются  
**Решение:** Проверьте, что модуль установлен и активен в административной панели

**Проблема:** Ошибка "Модуль не найден"  
**Решение:** Убедитесь, что файлы модуля скопированы в правильную директорию `/local/modules/mibazarow.favorites/`

**Проблема:** AJAX не работает  
**Решение:** Проверьте подключение jQuery и корректность путей к компонентам

### Логирование

Для отладки включите логирование в файле `/local/modules/mibazarow.favorites/lib/tools/favorites.php`:

```php
// Добавьте в начало метода
\Bitrix\Main\Diag\Debug::writeToFile($data, 'favorites_debug', '/local/logs/');
```

## Техническая поддержка

- **Email:** support@mibazarow.ru
- **Сайт:** http://mibazarow.ru
- **Документация:** [Ссылка на документацию]

## Лицензия

Модуль распространяется по лицензии MIT. Подробности в файле LICENSE.

## Changelog

### v1.0.1 (2024-06-08)
- Добавлена строгая типизация PHP 8+
- Рефакторинг архитектуры с использованием пространств имен
- Улучшена производительность компонентов
- Исправлены мелкие ошибки

### v1.0.0 (2024-01-01)
- Первый релиз модуля
- Базовая функциональность избранного
- Административный интерфейс
- Три основных компонента