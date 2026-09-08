<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

if (empty($arResult['ITEMS'])) {
    return;
}

if (!function_exists('htmlspecialcharsbx')) {
    function htmlspecialcharsbx($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

/** @var array $arResult */
?>
<section class="promo-cards" aria-labelledby="promo-cards-title">
    <div class="promo-cards__inner">
        <h2 class="promo-cards__title" id="promo-cards-title">
            <?= htmlspecialcharsbx(GetMessage('PROMO_CARDS_TITLE')) ?>
        </h2>

        <div class="promo-cards__grid">
            <?php foreach ($arResult['ITEMS'] as $item): ?>
                <?php
                $title = isset($item['NAME']) ? $item['NAME'] : '';
                $description = isset($item['PREVIEW_TEXT']) ? strip_tags($item['PREVIEW_TEXT']) : '';
                $discount = BitrixSalePromoCardData::getPropertyValue($item, 'DISCOUNT_PERCENT');
                $image = '';
                if (isset($item['PREVIEW_PICTURE']['SRC'])) {
                    $image = $item['PREVIEW_PICTURE']['SRC'];
                } elseif (isset($item['PREVIEW_PICTURE']) && is_string($item['PREVIEW_PICTURE'])) {
                    $image = $item['PREVIEW_PICTURE'];
                } elseif (!empty($item['PREVIEW_PICTURE']) && class_exists('CFile')) {
                    $image = CFile::GetPath((int) $item['PREVIEW_PICTURE']);
                }
                $detailUrl = isset($item['DETAIL_PAGE_URL']) ? $item['DETAIL_PAGE_URL'] : '#';
                ?>
                <article class="promo-card<?= !empty($item['IS_HOT']) ? ' promo-card--hot' : '' ?>">
                    <?php if ($image !== ''): ?>
                        <a class="promo-card__image-link" href="<?= htmlspecialcharsbx($detailUrl) ?>">
                            <img
                                class="promo-card__image"
                                src="<?= htmlspecialcharsbx($image) ?>"
                                alt="<?= htmlspecialcharsbx($title) ?>"
                                loading="lazy"
                            >
                        </a>
                    <?php endif; ?>

                    <div class="promo-card__body">
                        <div class="promo-card__badges" aria-label="Метки акции">
                            <?php if (!empty($item['IS_HOT'])): ?>
                                <span class="promo-card__badge promo-card__badge--hot">
                                    <?= htmlspecialcharsbx(GetMessage('PROMO_CARDS_HOT_BADGE')) ?>
                                </span>
                            <?php endif; ?>
                            <span class="promo-card__badge">
                                <?= htmlspecialcharsbx($item['BADGE']) ?>
                            </span>
                        </div>

                        <h3 class="promo-card__name">
                            <a href="<?= htmlspecialcharsbx($detailUrl) ?>">
                                <?= htmlspecialcharsbx($title) ?>
                            </a>
                        </h3>

                        <?php if ($description !== ''): ?>
                            <p class="promo-card__description">
                                <?= htmlspecialcharsbx($description) ?>
                            </p>
                        <?php endif; ?>

                        <div class="promo-card__discount">
                            -<?= htmlspecialcharsbx($discount) ?>%
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
