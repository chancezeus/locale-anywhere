<?php

namespace Sloveniangooner\LocaleAnywhere;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CacheLocaleResolver implements Contracts\LocaleResolver
{
    /**
     * @return string
     */
    public function getLocale(): string
    {
        if ($key = $this->getCacheKey()) {
            return Cache::get($key) ?: App::getLocale();
        }

        return App::getLocale();
    }

    /**
     * @param string $locale
     * @return string
     */
    public function setLocale(string $locale): string
    {
        if ($key = $this->getCacheKey()) {
            Cache::forever($key, $locale);

            return Cache::get($key);
        }

        return $locale;
    }

    /**
     * @return string|null
     */
    protected function getCacheKey(): ?string
    {
        if ($prefix = Auth::id()) {
            return "{$prefix}.locale";
        }

        return null;
    }
}
