# Bitrix promo cards

Кастомный шаблон стандартного компонента `bitrix:news.list` для блока «Акции и спецпредложения».

## Структура

```text
local/
└── templates/
    └── .default/
        └── components/
            └── bitrix/
                └── news.list/
                    └── promo_cards/
                        ├── lang/ru/template.php
                        ├── lib/promo-card-data.php
                        ├── result_modifier.php
                        ├── style.css
                        └── template.php
```

Шаблон выбирается в настройках компонента `bitrix:news.list` как `promo_cards`.

Пользовательский шаблон хранится в `/local/templates/.default/`; каталог
`components/bitrix/` внутри него обозначает пространство имён стандартного
компонента `bitrix:news.list` и сохраняется без переименования.
Если шаблон нужен только одному шаблону сайта, вместо `.default` используйте
его идентификатор: `/local/templates/<site_template_id>/components/bitrix/news.list/promo_cards/`.

## Установка и перенос

1. Скопируйте каталог `local/` из репозитория в корень установленного сайта.
2. Если ранее использовалась версия из `/bitrix/templates/.default/components/bitrix/news.list/promo_cards/`,
   сохраните резервную копию вне корня сайта и удалите только этот старый каталог
   `promo_cards` после проверки переноса. Ядро и стандартные компоненты в `/bitrix/` переносить не нужно.
3. Убедитесь, что в активном шаблоне сайта нет другого шаблона `promo_cards`,
   который будет выбран вместо общего из `.default`.
4. Выберите `promo_cards` в настройках компонента, сбросьте его кеш и проверьте блок на странице.

Документация: [папка /local](https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=2705&LESSON_PATH=3913.4776.2483.2705).

## Логика

- `IS_HOT` равен `true`, если до `DATE_ACTIVE_TO` осталось больше 0 и меньше 72 часов.
- При скидке строго больше 20% устанавливается `BADGE = «Суперцена»`, иначе `BADGE = «Выгода»`.
- Нормализованный бейдж доступен и в `$item['BADGE']`, и в `$item['PROPERTIES']['BADGE']['VALUE']`, если свойство было возвращено компонентом.
- Значения, выводимые в HTML, экранируются средствами Bitrix.

## Подключение

В параметрах компонента укажите:

```php
'COMPONENT_TEMPLATE' => 'promo_cards'
```

Стандартный `news.list` должен возвращать `NAME`, `PREVIEW_PICTURE`, `PREVIEW_TEXT`, `DETAIL_PAGE_URL`, `DATE_ACTIVE_TO` и свойства `DISCOUNT_PERCENT` и `BADGE`.

## Проверка

Из корня репозитория:

```bash
php tests/promo-card-data-test.php
php -l local/templates/.default/components/bitrix/news.list/promo_cards/result_modifier.php
php -l local/templates/.default/components/bitrix/news.list/promo_cards/template.php
php -l local/templates/.default/components/bitrix/news.list/promo_cards/lib/promo-card-data.php
```

## Визуальный preview без Bitrix

Если полноценного Bitrix-сайта нет, откройте `preview.html` в браузере из корня репозитория. Файл использует тот же `style.css` и содержит тестовые карточки для проверки сетки, бейджей, изображения и адаптивности.

Для запуска через локальный HTTP-сервер:

```bash
python3 -m http.server 8080
```

После этого откройте <http://localhost:8080/preview.html>.
