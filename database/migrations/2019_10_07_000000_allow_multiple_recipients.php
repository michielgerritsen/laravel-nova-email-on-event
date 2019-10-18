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

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MichielGerritsen\LaravelNovaEmailOnEvent\Models\EmailEvent;

class AllowMultipleRecipients extends Migration
{
    public function up()
    {
        EmailEvent::chunk(100, function ($chunk) {
            /** @var EmailEvent $model */
            foreach ($chunk as $model) {
                $model->to = json_encode($model->to);
                $model->save();
            }
        });
    }
}
