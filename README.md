# Bitrix promo cards

Кастомный шаблон стандартного компонента `bitrix:news.list` для блока «Акции и спецпредложения».

## Структура

```text
bitrix/
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
php -l bitrix/templates/.default/components/bitrix/news.list/promo_cards/result_modifier.php
php -l bitrix/templates/.default/components/bitrix/news.list/promo_cards/template.php
php -l bitrix/templates/.default/components/bitrix/news.list/promo_cards/lib/promo-card-data.php
```
