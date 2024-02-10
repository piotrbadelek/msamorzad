<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class PaymentCreated extends Notification
{
	use Queueable;

	/**
	 * Create a new notification instance.
	 */

	protected string $paymentName;

	public function __construct(string $paymentName)
	{
		$this->paymentName = $paymentName;
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @return array<int, string>
	 */
	public function via(object $notifiable): array
	{
		return [WebPushChannel::class];
	}

	public function toWebPush($notifiable, $notification)
	{
		return (new WebPushMessage)
			->title('Nowa składka')
			->icon('/img/touch/192.png')
			->body('W twojej klasie utworzono nową składkę: ' . $this->paymentName)
			->options(['TTL' => 1000]);
		// ->data(['id' => $notification->id])
		// ->badge()
		// ->dir()
		// ->image()
		// ->lang()
		// ->renotify()
		// ->requireInteraction()
		// ->tag()
		// ->vibrate()
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(object $notifiable): array
	{
		return [
			//
		];
	}
}
