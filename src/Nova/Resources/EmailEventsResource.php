<?php
/**
 *    ______            __             __
 *   / ____/___  ____  / /__________  / /
 *  / /   / __ \/ __ \/ __/ ___/ __ \/ /
 * / /___/ /_/ / / / / /_/ /  / /_/ / /
 * \______________/_/\__/_/   \____/_/
 *    /   |  / / /_
 *   / /| | / / __/
 *  / ___ |/ / /_
 * /_/ _|||_/\__/ __     __
 *    / __ \___  / /__  / /____
 *   / / / / _ \/ / _ \/ __/ _ \
 *  / /_/ /  __/ /  __/ /_/  __/
 * /_____/\___/_/\___/\__/\___/
 *
 */

namespace MichielGerritsen\LaravelNovaEmailOnEvent\Nova\Resources;

use App\Events\ShopBilled;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use MichielGerritsen\LaravelNovaEmailOnEvent\Models\EmailEvent;
use MichielGerritsen\LaravelNovaEmailOnEvent\Nova\Fields\EmailField;
use MichielGerritsen\LaravelNovaEmailOnEvent\Service\Files\ClassNameFinder;
use MichielGerritsen\LaravelNovaEmailOnEvent\Service\Files\EventFinder;

class EmailEventsResource extends \Laravel\Nova\Resource
{
    public static $model = EmailEvent::class;

    public static function label()
    {
        return trans('Email Events');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Select::make('Event')->options(app(EventFinder::class)->find())->required(),
            Text::make('From')->required(),
            EmailField::make('To')->required(),
            EmailField::make('Cc')->hideFromIndex(),
            EmailField::make('Bcc')->hideFromIndex(),
            Text::make('Subject')->required(),
            Trix::make('Message')->hideFromIndex()->required(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
