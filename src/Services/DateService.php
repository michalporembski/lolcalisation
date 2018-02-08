<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class DateService
 *
 * @package App\Services
 */
class DateService
{
    /**
     * List of Date formats per Locales
     */
    const DATE_FORMAT = [
        Locale::LOCALE_EN_US => 'n/j/y',
        Locale::LOCALE_EN_GB => 'd M Y',
        Locale::LOCALE_PL_PL => 'd.m.y',
    ];

    /**
     * List of Date and Time formats per Locales
     */
    const DATE_TIME_FORMAT = [
        Locale::LOCALE_EN_US => 'n/j/y g:i A',
        Locale::LOCALE_EN_GB => 'd M Y H:i',
        Locale::LOCALE_PL_PL => 'd.m.y H:i',
    ];

    /**
     * @var string
     */
    private $currentLocale;

    /**
     * DateService constructor.
     *
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->currentLocale = $requestStack->getCurrentRequest()->getLocale();
    }

    /**
     * @return \DateTime
     */
    public function getCurrentTime(): \DateTime
    {
        return new \DateTime();
    }

    /**
     * @param \DateTime $dateTime
     *
     * @return string
     */
    public function formatDate(\DateTime $dateTime): string
    {
        return $dateTime->format($this->getDateFormat());
    }

    /**
     * @param \DateTime $dateTime
     *
     * @return string
     */
    public function formatDateTime(\DateTime $dateTime): string
    {
        return $dateTime->format($this->getDateTimeFormat());
    }

    /**
     * @return string
     */
    private function getDateFormat(): string
    {
        if (isset(self::DATE_FORMAT[$this->currentLocale])) {
            return self::DATE_FORMAT[$this->currentLocale];
        }

        return self::DATE_FORMAT[Locale::DEFAULT_LOCALE];
    }

    /**
     * @return string
     */
    private function getDateTimeFormat(): string
    {
        if (isset(self::DATE_TIME_FORMAT[$this->currentLocale])) {
            return self::DATE_TIME_FORMAT[$this->currentLocale];
        }

        return self::DATE_TIME_FORMAT[Locale::DEFAULT_LOCALE];
    }
}
