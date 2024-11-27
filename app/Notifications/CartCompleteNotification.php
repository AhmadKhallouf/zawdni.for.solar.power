<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CartCompleteNotification extends Notification
{
    use Queueable;

    private $cart_id;
    private $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($cart_id,$user)
    {
        $this->cart_id = $cart_id;
        $this->user = $user;
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
            'message' => 'there is a cart complete for '.$this->user->first_name.' '.$this->user->last_name.' '.'that has ID : ' .$this->cart_id,
        ];
    }
}
