<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class YourCartIsProcessingNotification extends Notification
{
    use Queueable;

    private $cart_id;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($cart_id)
    {
        $this->cart_id = $cart_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => 'your cart that has an ID : '.$this->cart_id.', is in processing, that will take about 24 hours, thank you for trust',
        ];
    }
}
