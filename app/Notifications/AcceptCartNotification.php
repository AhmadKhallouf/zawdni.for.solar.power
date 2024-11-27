<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AcceptCartNotification extends Notification 
{
    use Queueable;

    protected $cart;
    protected $user_name;
    protected $supplement_price;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($cart,$user_name,$supplement_price)
    {
        $this->cart = $cart;
        $this->user_name = $user_name;
        $this->supplement_price = $supplement_price;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $cart = $this->cart;
        $supplement_price = $this->supplement_price;
        return (new MailMessage)
                    ->subject('Accept Cart Notification')
                    ->line('Hello' .$this->user_name)
                    ->line('Your cart with ID: ' . $this->cart->id . ' has been accepted please wait until the installation team to reach you.')
                    ->view('Bill_for_user',compact(['cart','supplement_price']))
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
            'message' => 'Your cart with ID: ' . $this->cart->id . ' has been accepted.',
        ];
    }
}
