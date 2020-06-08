<?php

return [
    // The locales available in the application
    'locales' => [
        'nl' => 'Dutch',
        'en' => 'English',
    ],

    // Resolves the currently selected locale
    'locale_resolver' => Sloveniangooner\LocaleAnywhere\CacheLocaleResolver::class,

    // Provides the locales to use
    'locales_provider' => Sloveniangooner\LocaleAnywhere\ConfigLocalesProvider::class,

    // Use the fallback locale
    'use_fallback' => true,

    // Use the custom detail toolbar
    'custom_detail_toolbar' => false,
];
