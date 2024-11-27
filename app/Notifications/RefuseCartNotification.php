<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RefuseCartNotification extends Notification
{
    use Queueable;

    protected $cart_id;
    protected $user_name;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($id,$user_name)
    {
        $this->cart_id = $id;
        $this->user_name = $user_name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                     ->subject('Refuse Cart Notification')
                     ->line('Hello'.' '.$this->user_name)
                     ->line('Your cart with ID: ' . $this->cart_id . ' has been refused, please contact with admins to detect the problem.')
                    // ->action('View Cart', url('/cart'))
                     ->line('Thank you for using our application!');
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
            'message' => 'Your cart with ID: ' . $this->cart_id . ' has been refused.',
        ];
    }
}
