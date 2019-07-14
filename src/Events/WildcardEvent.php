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

namespace MichielGerritsen\LaravelNovaEmailOnEvent\Events;

use Illuminate\Support\Str;
use MichielGerritsen\LaravelNovaEmailOnEvent\Models\EmailEvent;
use MichielGerritsen\LaravelNovaEmailOnEvent\Service\EmailSender;
use MichielGerritsen\LaravelNovaEmailOnEvent\Service\Files\EventFinder;

class WildcardEvent
{
    /**
     * @var EventFinder
     */
    private $eventFinder;

    /**
     * @var EmailSender
     */
    private $emailSender;

    /**
     * @var array
     */
    private $skipped = [
        'eloquent.',
        'Illuminate\\',
        'Laravel\\',
        'bootstrapped:'
    ];

    public function __construct(
        EventFinder $eventFinder,
        EmailSender $emailSender
    ) {
        $this->eventFinder = $eventFinder;
        $this->emailSender = $emailSender;
    }

    public function handle($eventName, $data)
    {
        if (Str::startsWith($eventName, $this->skipped)) {
            return null;
        }

        $eventName = '\\' . $eventName;
        if (!$this->eventFinder->hasEvent($eventName)) {
            return null;
        }

        $mails = \Cache::remember('email-events.events.' . $eventName, 60 * 60, function () use ($eventName) {
            return EmailEvent::where('event', $eventName)->get();
        });

        foreach ($mails as $mail) {
            $this->emailSender->send($mail, $data[0]);
        }

        return null;
    }
}
