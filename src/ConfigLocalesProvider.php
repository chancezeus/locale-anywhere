<?php

namespace Sloveniangooner\LocaleAnywhere;

use Illuminate\Support\Facades\Config;

class ConfigLocalesProvider implements Contracts\LocalesProvider
{
    /**
     * @return string[]
     */
    public function getLocales(): array
    {
        return Config::get('locale-anywhere.locales');
    }
}
