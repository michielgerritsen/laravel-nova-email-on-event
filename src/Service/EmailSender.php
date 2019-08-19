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

namespace MichielGerritsen\LaravelNovaEmailOnEvent\Service;

use Illuminate\Mail\Message;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use MichielGerritsen\LaravelNovaEmailOnEvent\Models\EmailEvent;

class EmailSender
{
    /**
     * @var EmailContent
     */
    private $emailContent;

    public function __construct(
        EmailContent $emailContent
    ) {
        $this->emailContent = $emailContent;
    }

    public function send(EmailEvent $email, $event)
    {
        $content = $this->emailContent->prepare($email->message, $event);

        Mail::html($content, function (Message $message) use ($email, $event) {
            $to = $this->emailContent->prepare($email->to, $event);
            $subject = $this->emailContent->prepare($email->subject, $event);

            $message->to($to);
            $message->from($email->from);
            $message->subject($subject);
        });
    }
}
