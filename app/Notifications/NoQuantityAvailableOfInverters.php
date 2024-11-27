<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NoQuantityAvailableOfInverters extends Notification
{
    use Queueable;
    
    private $inverter_watt;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($inverter_watt)
    {
        $this->inverter_watt = $inverter_watt;
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
            'message' => 'there is no quantity available of inverters that have a value watt : '.$this->inverter_watt.' W, there is requests for this type.',
        ];
    }
}
