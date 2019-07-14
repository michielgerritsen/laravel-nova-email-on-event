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

namespace MichielGerritsen\LaravelNovaEmailOnEvent\Test\Events;

use Illuminate\Support\Facades\Cache;
use MichielGerritsen\LaravelNovaEmailOnEvent\Events\WildcardEvent;
use MichielGerritsen\LaravelNovaEmailOnEvent\Models\EmailEvent;
use MichielGerritsen\LaravelNovaEmailOnEvent\Service\EmailSender;
use MichielGerritsen\LaravelNovaEmailOnEvent\Service\Files\EventFinder;
use MichielGerritsen\LaravelNovaEmailOnEvent\Test\Fakes\FlexibleEvent;
use Orchestra\Testbench\TestCase;

class WildcardEventTest extends TestCase
{
    public function testIgnoresSystemEvents()
    {
        /** @var WildcardEvent $instance */
        $instance = app(WildcardEvent::class);
        $result = $instance->handle('eloquent.model.save', []);

        $this->assertNull($result);
    }

    public function testReturnsNullWhenThereAreNoRelevantEvents()
    {
        $eventFinderMock = $this->createMock(EventFinder::class);
        $eventFinderMock->expects($this->once())->method('hasEvent')->willReturn(false);

        /** @var WildcardEvent $instance */
        $instance = app(WildcardEvent::class, [
            'eventFinder' => $eventFinderMock,
        ]);
        $result = $instance->handle('App\\Events\\CustomEvent', []);

        $this->assertNull($result);
    }

    public function testSendsEmailsWhenApplicable()
    {
        $eventFinderMock = $this->createMock(EventFinder::class);
        $eventFinderMock->expects($this->once())->method('hasEvent')->willReturn(true);

        $emailSenderMock = $this->createMock(EmailSender::class);
        $emailSenderMock->expects($this->once())->method('send');

        $eventName = 'App\\Events\\CustomEvent';
        $collection = collect([new EmailEvent()]);
        Cache::put('email-events.events.\\' . $eventName, $collection);

        /** @var WildcardEvent $instance */
        $instance = app(WildcardEvent::class, [
            'eventFinder' => $eventFinderMock,
            'emailSender' => $emailSenderMock,
        ]);

        $result = $instance->handle($eventName, [new FlexibleEvent]);
        $this->assertNull($result);
    }
}
