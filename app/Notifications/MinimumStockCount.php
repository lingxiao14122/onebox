<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class MinimumStockCount extends Notification implements ShouldQueue
{
    use Queueable;

    protected $item;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->item = $item;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $item = $this->item;
        return (new MailMessage)
                    ->subject("Minimum Stock Count Reached $item->name")
                    ->greeting("Hi there,")
                    ->line("$item->name has fallen below the minimum stock count.")
                    ->line("SKU code is $item->sku")
                    ->line("Quantity left $item->stock_count")
                    ->action('See product detail', url("/item/$item->id"))
                    ->line('Thank you for using Onebox');
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
            //
        ];
    }
}
