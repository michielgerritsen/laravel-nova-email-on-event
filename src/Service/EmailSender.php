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

    /**
     * @var EmailEvent
     */
    private $email;

    /**
     * @var Object
     */
    private $event;

    public function __construct(
        EmailContent $emailContent
    ) {
        $this->emailContent = $emailContent;
    }

    public function send(EmailEvent $email, $event)
    {
        $this->email = $email;
        $this->event = $event;

        $content = $this->emailContent->prepare($this->email->message, $this->event);
        Mail::html($content, [$this, 'composeMessage']);
    }

    public function composeMessage(Message $message)
    {
        $this->addRecipients($message, 'to', $this->event);
        $this->addRecipients($message, 'cc', $this->event);
        $this->addRecipients($message, 'bcc', $this->event);

        $subject = $this->emailContent->prepare($this->email->subject, $this->event);
        $from = $this->emailContent->prepare($this->email->from, $this->event);

        $message->from($from);
        $message->subject($subject);
    }

    private function addRecipients(Message $message, string $recipientType, $event)
    {
        if (!in_array($recipientType, ['to', 'cc', 'bcc'])) {
            throw new \Exception(sprintf('Unknown recipient type: %1', $recipientType));
        }

        $recipients = json_decode($this->email->$recipientType, JSON_OBJECT_AS_ARRAY);

        $recipients = array_map(function ($recipient) {
            return $this->emailContent->prepare($recipient, $this->event);
        }, $recipients);

        $recipients = array_filter($recipients, function ($recipient) {
            return filter_var($recipient, FILTER_VALIDATE_EMAIL);
        });

        if (!$recipients) {
            return;
        }

        $message->{'set' . ucfirst($recipientType)}($recipients);
    }
}
