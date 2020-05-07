<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Discord\DiscordChannel;
use NotificationChannels\Discord\DiscordMessage;
use NotificationChannels\Discord\Discord;
use App\Models\Evemail;

class EveMailDiscord extends Notification
{
    use Queueable;
    public $evemail;
    protected $message;
    protected $subject;
    protected $from;
    protected $mention;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message,$subject,$from,$mention)
    {
        //
        $this->message = $message;
        $this->subject = $subject;
        $this->from = $from;
        $this->mention = $mention;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
//    public function via($notifiable)
//    {
//        return ['discord'];
//    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }
    
    public function via($notifiable)
    {
        return [DiscordChannel::class];
    }

    public function toDiscord($notifiable)
    {
//        return DiscordMessage::create($this->mention." `От:".$this->from."` `Тема: ".$this->subject."` ```".$this->message."```");
        $embed['title'] = ":e_mail: `От: ".$this->from."`\n`Тема: ".$this->subject."`";
        $embed['description'] = "```".$this->message."```";
        return DiscordMessage::create($this->mention.' '.$this->subject,$embed);
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
