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

namespace MichielGerritsen\LaravelNovaEmailOnEvent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * @property $event
 * @property $to
 * @property $from
 * @property $subject
 * @property $message
 */
class EmailEvent extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::saving( function (EmailEvent $model) {
            Cache::forget('email-events.event-list');
            Cache::forget('email-events.events.' . $model->event);
        });
    }

}