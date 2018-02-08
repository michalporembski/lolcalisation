<?php

namespace App\Services;

/**
 * Class Locale
 *
 * @package App\Services
 */
class Locale
{
    /**
     * English locale for United States
     */
    const LOCALE_EN_US = 'en_US';

    /**
     * English locale for United Kingdom
     */
    const LOCALE_EN_GB = 'en_GB';

    /**
     * Polish Locale for Poland
     */
    const LOCALE_PL_PL = 'pl_PL';

    /**
     * Default Locale
     */
    const DEFAULT_LOCALE = self::LOCALE_EN_US;

    /**
     * Available locale translations
     */
    const AVAILABLE_LOCALES = [
        self::LOCALE_EN_US => 'localisation.locale.english-us',
        self::LOCALE_EN_GB => 'localisation.locale.english-gb',
        self::LOCALE_PL_PL => 'localisation.locale.polish-pl'
    ];
}
