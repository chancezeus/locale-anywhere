<?php

namespace Sloveniangooner\LocaleAnywhere;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Spatie\Translatable\HasTranslations as SpatieTrait;

trait HasTranslations
{
    use SpatieTrait;

    public function getAttributeValue($key)
    {
        if (!$this->isTranslatableAttribute($key)) {
            return parent::getAttributeValue($key);
        }

        // By default, we use fallback value
        $useFallback = Config::get('locale-anywhere.use_fallback') ?? true;

        // Local model fallback settings
        if (property_exists($this, 'useFallback')) {
            $useFallback = $this->useFallback;
        }

        return $this->getTranslation($key, $this->getLocale(), $useFallback);
    }

    /**
     * @return string
     */
    protected function getLocale(): string
    {
        /** @var \Sloveniangooner\LocaleAnywhere\Contracts\LocaleResolver $localeResolver */
        $localeResolver = App::make(Contracts\LocaleResolver::class);

        return $localeResolver->getLocale();
    }
}
