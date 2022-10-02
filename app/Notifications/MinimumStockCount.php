<?php

namespace App\Notifications;

use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class MinimumStockCount extends Notification implements ShouldQueue
{
    use Queueable;

    private $item;
    private $count;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($item, $count)
    {
        $this->item = $item;
        $this->count = $count;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'broadcast', 'database'];
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
            ->line("Quantity left $item->count")
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
            'item_id' => $this->item->id,
            'name' => $this->item->name,
            'stock_count' => $this->count,
        ];
    }

    /**
     * Determine the notification's delivery delay.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function withDelay($notifiable)
    {
        return [
            'database' => now()->addSeconds(3),
        ];
    }
}
