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

namespace MichielGerritsen\LaravelNovaEmailOnEvent\Service\Files;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use MichielGerritsen\LaravelNovaEmailOnEvent\Models\EmailEvent;

class EventFinder
{
    /**
     * @var ClassNameFinder
     */
    private $classNameFinder;

    /**
     * @var array
     */
    private $files = [];

    public function __construct(
        ClassNameFinder $classNameFinder
    ) {
        $this->classNameFinder = $classNameFinder;
    }

    public function find()
    {
        $this->findEventsInPath(app_path('Events'));

        return $this->files;
    }

    public function hasEvent($eventName)
    {
        /** @var Collection $events */
        $events = \Cache::remember('email-events.event-list', 60, function () {
            return EmailEvent::groupBy('event')->pluck('event');
        });

        return $events->contains($eventName);
    }

    private function findEventsInPath($path)
    {
        $iterator = new \RecursiveDirectoryIterator($path);

        /** @var \SplFileInfo $file */
        foreach (new \RecursiveIteratorIterator($iterator) as $file) {
            if (!Str::endsWith($file->getRealPath(), '.php')) {
                continue;
            }

            $classInfo = $this->classNameFinder->fromFile($file);

            $this->files[$classInfo->getNamespace()] = $this->getReadableName($classInfo->getName());
        }
    }

    private function getReadableName($name)
    {
        preg_match_all('/((?:^|[A-Z])[a-z]+)/', $name,$matches);

        return implode(' ', $matches[0]);
    }
}
