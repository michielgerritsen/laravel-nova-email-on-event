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

namespace MichielGerritsen\LaravelNovaEmailOnEvent\Test\Unit\Service;

use MichielGerritsen\LaravelNovaEmailOnEvent\Service\EmailContent;
use MichielGerritsen\LaravelNovaEmailOnEvent\Test\Fakes\FlexibleEvent;
use PHPUnit\Framework\TestCase;

class EmailContentTest extends TestCase
{
    public function replacesTheContentProvider()
    {
        return [
            'non-object' => ['This is a {$type} for John Doe: Hello', 'This is a message for John Doe: Hello'],
            'object' => ['This is a message for {$user->name}: Hello', 'This is a message for John Doe: Hello'],
            'nested-object' => ['Please send this to {$user->address->street}', 'Please send this to ExampleStreet'],
        ];
    }

    /**
     * @dataProvider replacesTheContentProvider
     */
    public function testReplacesTheContent($input, $output)
    {
        $user = new \stdClass();
        $user->name = 'John Doe';
        $user->address = new \stdClass();
        $user->address->street = 'ExampleStreet';

        $event = new FlexibleEvent;
        $event->user = $user;
        $event->type = 'message';

        /** @var EmailContent $instance */
        $instance = app(EmailContent::class);
        $result = $instance->prepare($input, $event);

        $this->assertEquals($output, $result);
    }
}
