<?php

namespace Sloveniangooner\LocaleAnywhere\Contracts;

interface LocalesProvider
{
    /**
     * @return string[]
     */
    public function getLocales(): array;
}
