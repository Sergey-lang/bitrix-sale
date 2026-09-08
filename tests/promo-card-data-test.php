<?php

require_once __DIR__ . '/../bitrix/templates/.default/components/bitrix/news.list/promo_cards/lib/promo-card-data.php';

function assertSameValue($expected, $actual, $message)
{
    if ($expected !== $actual) {
        throw new RuntimeException($message . "\nExpected: " . var_export($expected, true) . "\nActual: " . var_export($actual, true));
    }
}

$now = strtotime('2026-09-08 12:00:00');

assertSameValue(
    true,
    BitrixSalePromoCardData::isHot('11.09.2026 11:59:59', $now),
    'An offer ending in less than three days should be hot.'
);
assertSameValue(
    false,
    BitrixSalePromoCardData::isHot('11.09.2026 12:00:00', $now),
    'An offer ending exactly in three days should not be hot.'
);
assertSameValue(
    false,
    BitrixSalePromoCardData::isHot('08.09.2026 11:59:59', $now),
    'An expired offer should not be hot.'
);
assertSameValue('Суперцена', BitrixSalePromoCardData::getBadge(21), 'Discounts above 20% get the Superprice badge.');
assertSameValue('Выгода', BitrixSalePromoCardData::getBadge('20'), 'Discounts of 20% or less get the Benefit badge.');

$item = BitrixSalePromoCardData::enrich(
    [
        'DATE_ACTIVE_TO' => '09.09.2026 12:00:00',
        'PROPERTIES' => [
            'DISCOUNT_PERCENT' => ['VALUE' => '25'],
            'BADGE' => ['VALUE' => 'Старая метка'],
        ],
    ],
    $now
);

assertSameValue(true, $item['IS_HOT'], 'The enriched item should contain IS_HOT.');
assertSameValue('Суперцена', $item['BADGE'], 'The enriched item should contain the normalized badge.');
assertSameValue('Суперцена', $item['PROPERTIES']['BADGE']['VALUE'], 'The property badge should be normalized too.');

echo "Promo card data tests passed.\n";
