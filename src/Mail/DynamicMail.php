<?php

namespace App\Mail;

use App\Models\Shop;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DynamicMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Shop
     */
    public $shop;

    /**
     * @var array
     */
    public $data;

    public function __construct(Shop $shop, $data = [])
    {
        $this->shop = $shop;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.contact', $this->data + ['shop' => $this->shop]);
    }
}
