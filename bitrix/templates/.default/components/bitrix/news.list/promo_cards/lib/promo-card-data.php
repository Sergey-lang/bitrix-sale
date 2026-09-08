<?php

/**
 * Data preparation for the promo_cards news.list template.
 *
 * The class intentionally has no dependency on Bitrix APIs so that the rules
 * can be checked in isolation and reused by a result_modifier.php.
 */
class BitrixSalePromoCardData
{
    const HOT_PERIOD_SECONDS = 259200;

    /**
     * Adds template-ready values to one news.list item.
     *
     * @param array $item
     * @param int|null $now Unix timestamp used for the hot-card calculation.
     * @return array
     */
    public static function enrich(array $item, $now = null)
    {
        $discount = self::getPropertyValue($item, 'DISCOUNT_PERCENT');
        $item['IS_HOT'] = self::isHot(self::getDateActiveTo($item), $now);
        $item['BADGE'] = self::getBadge($discount);

        // Keep the normalized value available through the standard property
        // shape as well, when the component returned the property.
        if (isset($item['PROPERTIES']['BADGE'])) {
            $item['PROPERTIES']['BADGE']['VALUE'] = $item['BADGE'];
        }

        return $item;
    }

    /**
     * An active card is hot only while its end date is in the future and less
     * than three 24-hour periods away. Expired and invalid dates are not hot.
     *
     * @param string|int|null $dateActiveTo
     * @param int|null $now
     * @return bool
     */
    public static function isHot($dateActiveTo, $now = null)
    {
        $endTimestamp = self::toTimestamp($dateActiveTo);

        if ($endTimestamp === null) {
            return false;
        }

        if ($now === null) {
            $now = time();
        }

        $secondsLeft = $endTimestamp - (int) $now;

        return $secondsLeft > 0 && $secondsLeft < self::HOT_PERIOD_SECONDS;
    }

    /**
     * @param mixed $discount
     * @return string
     */
    public static function getBadge($discount)
    {
        return (float) str_replace(',', '.', (string) $discount) > 20
            ? 'Суперцена'
            : 'Выгода';
    }

    /**
     * @param array $item
     * @param string $code
     * @return mixed|null
     */
    public static function getPropertyValue(array $item, $code)
    {
        if (isset($item['PROPERTIES'][$code]['VALUE'])) {
            return $item['PROPERTIES'][$code]['VALUE'];
        }

        return isset($item[$code]) ? $item[$code] : null;
    }

    /**
     * DATE_ACTIVE_TO is normally a news.list field, but accepting the property
     * shape too makes the template tolerant of test fixtures and custom queries.
     *
     * @param array $item
     * @return mixed|null
     */
    private static function getDateActiveTo(array $item)
    {
        if (!empty($item['DATE_ACTIVE_TO'])) {
            return $item['DATE_ACTIVE_TO'];
        }

        return self::getPropertyValue($item, 'DATE_ACTIVE_TO');
    }

    /**
     * @param mixed $date
     * @return int|null
     */
    private static function toTimestamp($date)
    {
        if (is_int($date) || (is_string($date) && ctype_digit($date))) {
            return (int) $date;
        }

        if (!is_string($date) || trim($date) === '') {
            return null;
        }

        $timestamp = strtotime($date);

        return $timestamp === false ? null : $timestamp;
    }
}
