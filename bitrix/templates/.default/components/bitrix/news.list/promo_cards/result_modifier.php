<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

require_once __DIR__ . '/lib/promo-card-data.php';

foreach ($arResult['ITEMS'] as $itemIndex => $item) {
    $arResult['ITEMS'][$itemIndex] = BitrixSalePromoCardData::enrich($item);
}
