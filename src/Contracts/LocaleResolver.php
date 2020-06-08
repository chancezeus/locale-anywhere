<?php

namespace Sloveniangooner\LocaleAnywhere\Contracts;

interface LocaleResolver
{
    /**
     * @return string
     */
    public function getLocale(): string;

    /**
     * @param string $locale
     * @return string
     */
    public function setLocale(string $locale): string;
}
