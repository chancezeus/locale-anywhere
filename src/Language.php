<?php

namespace Sloveniangooner\LocaleAnywhere;

use Illuminate\Support\Facades\App;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Sloveniangooner\LocaleAnywhere\Contracts\LocaleResolver;
use Sloveniangooner\LocaleAnywhere\Contracts\LocalesProvider;

class Language extends Field
{
    public $component = "locale-anywhere";

    public $showOnIndex = false;

    public $locale;

    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        /** @var \Sloveniangooner\LocaleAnywhere\Contracts\LocaleResolver $localeResolver */
        $localeResolver = App::make(LocaleResolver::class);

        $this->locale = $localeResolver->getLocale();

        /** @var \Sloveniangooner\LocaleAnywhere\Contracts\LocalesProvider $localesProvider */
        $localesProvider = App::make(LocalesProvider::class);

        $this->withMeta([
            "locales" => $localesProvider->getLocales(),
            "locale" => $this->locale,
        ]);
    }

    public function fill(NovaRequest $request, $model)
    {
        return;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model|\Sloveniangooner\LocaleAnywhere\HasTranslations $resource
     * @param string $attribute
     * @return array
     */
    public function resolveAttribute($resource, $attribute)
    {
        $isTranslated = collect($resource->getTranslatableAttributes())
            ->contains(function (string $attribute) use ($resource) {
                return array_key_exists($this->locale, $resource->getTranslations($attribute));
            });

        return [
            "isTranslated" => $isTranslated,
            "value" => data_get($resource, str_replace('->', '.', $attribute))
        ];
    }
}
