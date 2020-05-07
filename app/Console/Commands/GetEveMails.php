<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\EveAuthController;
use App\Models\Evemail;
use App\Notifications\EveMailDiscord;
use NotificationChannels\Discord\Exceptions\CouldNotSendNotification;

class GetEveMails extends Command
{
    protected $eveauth;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:evemails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get eve mails';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(EveAuthController $eveauth)
    {
        $this->eveauth = $eveauth;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $evemails = $this->eveauth->getevemails();
//        dd($evemails);
        foreach($evemails as $evemail) {
            if (!Evemail::where('mail_id',$evemail['mail_id'])->first()) {
                $evemail_model = new Evemail($evemail);
                $evemail_model->recipient_id = $evemail['recipients'][0]['recipient_id'];
                $evemail_model->recipient_type = $evemail['recipients'][0]['recipient_type'];
//                dd($disc->toDiscord());
                $mail = $this->eveauth->getevemail($evemail_model->mail_id);
                $message = $mail['body'];
                $subject = $mail['subject'];
                $from = $this->eveauth->getcharacter($mail['from'])['name'];
                $recipient = $evemail_model->recipient_id;
                $channels = \DB::table('channels_maillists')->where('maillist',(int)$recipient)->get();
                if ($evemail_model->save()) {
                    foreach($channels as $channel) {
    //                    $evemail_model->setDiscordChannel("493874368762478594");
                            $message = "\n".str_replace('<br>',"\n",$message);
                            $message = strip_tags($message);
                            $evemail_model->setDiscordChannel($channel->channel_id);
                            if(strlen($message)>=2000) $message = substr($message,0,1999);
                            $disc = new EveMailDiscord($message,$subject,$from,$channel->mention);
                            try{
                                $evemail_model->notify($disc);
                            } catch (\NotificationChannels\Discord\Exceptions\CouldNotSendNotification $e) {
                            }
                    }
                }
            }
        }
//        dd($evemails);
        $this->info('Got eve mails ');
        
    }
}
