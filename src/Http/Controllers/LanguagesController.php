<?php

namespace Sloveniangooner\LocaleAnywhere\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravel\Nova\Actions\Actionable;
use Laravel\Nova\Actions\ActionEvent;
use Laravel\Nova\Http\Controllers\DeletesFields;
use Laravel\Nova\Nova;
use Sloveniangooner\LocaleAnywhere\Contracts\LocaleResolver;
use Sloveniangooner\LocaleAnywhere\Contracts\LocalesProvider;

class LanguagesController extends Controller
{
    use DeletesFields;

    /** @var \Sloveniangooner\LocaleAnywhere\Contracts\LocaleResolver */
    private $localeResolver;

    /** @var \Sloveniangooner\LocaleAnywhere\Contracts\LocalesProvider */
    private $localesProvider;

    /**
     * @param \Sloveniangooner\LocaleAnywhere\Contracts\LocaleResolver $localeResolver
     * @param \Sloveniangooner\LocaleAnywhere\Contracts\LocalesProvider $localesProvider
     */
    public function __construct(LocaleResolver $localeResolver, LocalesProvider $localesProvider)
    {
        $this->localeResolver = $localeResolver;
        $this->localesProvider = $localesProvider;
    }

    /**
     * @return string[]
     */
    public function languages(): array
    {
        return $this->localesProvider->getLocales();
    }

    public function cacheLocale(Request $request)
    {
        return $this->localeResolver->setLocale($request->input('locale'));
    }

    public function delete(Request $request)
    {
        $locale = $this->localeResolver->getLocale();

        $resourceClass = Nova::resourceForKey($request->get("resourceName"));
        if (!$resourceClass) {
            abort(404, "Missing resource class");
        }

        $modelClass = $resourceClass::$model;

        $resource = $modelClass::find($request->get("resourceId"));
        if (!$resource) {
            abort(404, "Missing resource");
        }

        // If translations count === 1 then forget the model completely
        $translationsCount = count($resource->getTranslations(
            $resource->getTranslatableAttributes()[0]
        ));

        if ($translationsCount > 1 and $resource->forgetAllTranslations($locale)->save()) {
            return response()->json(["status" => true]);
        }

        if ($translationsCount === 1) {
            if (in_array(Actionable::class, class_uses_recursive($resource))) {
                $resource->actions()->delete();
            }

            $resource->delete();

            DB::table('action_events')->insert(
                ActionEvent::forResourceDelete($request->user(), collect([$resource]))
                    ->map->getAttributes()->all()
            );

            return response()->json(["status" => true]);
        }

        abort(500, "Error saving");
    }
}
