<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class PaymentDueSoon extends Notification
{
	use Queueable;

	/**
	 * Create a new notification instance.
	 */
	protected int $daysRemaining;
	protected int $paymentAmount;
	public function __construct(int $daysRemaining, int $paymentAmount)
	{
		$this->daysRemaining = $daysRemaining;
		$this->paymentAmount = $paymentAmount;
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
		if ($this->daysRemaining > 4) {
			$notificationBody = "Pozostały " . $this->daysRemaining . " dni na uiszczenie płatności w wysokości " . $this->paymentAmount . " zł.";
		} else if ($this->daysRemaining == 1) {
			$notificationBody = "Pozostał " . $this->daysRemaining . " dzień na uiszczenie płatności w wysokości " . $this->paymentAmount . " zł.";
		} else {
			$notificationBody = "Pozostało " . $this->daysRemaining . " dni na uiszczenie płatności w wysokości " . $this->paymentAmount . " zł.";
		}

		return (new WebPushMessage)
			->title('Upływa termin płatności składki.')
			->icon('/img/touch/256.png')
			->body($notificationBody)
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
