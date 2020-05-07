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

class EveNotifDiscord extends Notification
{
    use Queueable;
    public $evemail;
    protected $message;
    protected $mention;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message,$mention)
    {
        //
        $this->message = $message;
        $this->mention = $mention;
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
//        $embed = " :warning: `Структура атакована!`
//                                      Система: [".$this->message['solar_name']."](http://evemaps.dotlan.net/route/5ZXX-K:".$this->message['solar_name'].")";
        $char_name = !empty($this->message['charName'])?("Игрок [".$this->message['charName']."](https://zkillboard.com/character/".$this->message['charID'].")\n"):"";
        $corp_name = !empty($this->message['corpName'])?("Корпорация ".$this->message['corpName']."\n"):"";
        $alliance_name = !empty($this->message['allianceName'])?("Альянс [".$this->message['allianceName']."](https://zkillboard.com/alliance/".$this->message['allianceID'].")\n"):"";
        $embed['title'] = '`'.$this->message['type_text'].'!`';
        $embed['description'] = 'Система ['.$this->message['solarName'].'](http://evemaps.dotlan.net/route/5ZXX-K:'.$this->message['solarName'].")\n".
                                'Время атаки '.$this->message['time']." ET\n".
                                $char_name.
                                $corp_name.
                                $alliance_name.
                                "Структура ".$this->message['structureName'];
        return DiscordMessage::create($this->mention.' :warning: `'.$this->message['type_text'].'!`',$embed);
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
