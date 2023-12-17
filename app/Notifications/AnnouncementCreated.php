<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class AnnouncementCreated extends Notification
{
	use Queueable;

	/**
	 * Create a new notification instance.
	 */

	protected string $announcementTitle;
	public function __construct(string $announcementTitle)
	{
		$this->announcementTitle = $announcementTitle;
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
			->title('Nowe ogłoszenie')
			->icon('/img/touch/256.png')
			->body('Dodano nowe ogłoszenie: ' . $this->announcementTitle)
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