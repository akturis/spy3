<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\EveAuthController;
use App\Models\EveNotifs;
use App\Models\NotifEve;
use App\Notifications\EveNotifDiscord;
use NotificationChannels\Discord\Exceptions\CouldNotSendNotification;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class GetEveNotif extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:evenotif';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get eve notification';

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
        $message_keys = array();
        $notifications = NotifEve::where('enabled',1)->get();
        foreach($notifications as $notification) {
            $evenotifies = $this->eveauth->getevenotifications($notification->character_id,$notification->token);
    //        dd($evemails);
            foreach($evenotifies as $evenotify) {
                if (!EveNotifs::where('notification_id',$evenotify['notification_id'])->first()) {
                    $evenotify['timestamp'] = date('Y-m-d H:i:s', strtotime($evenotify['timestamp']));
                    $this->info($evenotify['timestamp']);
                    $evenotify_model = new EveNotifs($evenotify);
//                    $evenotify_model->type = $evenotify['type'];
//                    $evenotify_model->text = $evenotify['text'];
    //                dd($disc->toDiscord());
                    $message = $evenotify['text'];
//                    $subject = $evenotify['text'];
//                    $from = $this->eveauth->getcharacter($mail['from'])['name'];
                    $type = $evenotify['type'];
                    $channels = \DB::table('channels_notifications')->where('type',$type)->get();
                    if ($evenotify_model->save()) {
                        foreach($channels as $channel) {
        //                    $evemail_model->setDiscordChannel("493874368762478594");
                            switch($type){
                                case 'StructureUnderAttack':
                                    preg_match_all('/(\w+)(?:: &id001 |: )(\d+|\w+ \w+|\w+)/',$message,$matches);
                                    $message_keys = array_combine ( $matches[1], $matches[2] );
                                    //$this->info(dd($message_keys));
                                    try{ 
                                        $response = $this->eveauth->getcharacter($message_keys['charID']);
                                        if(!(is_array($response)&&array_key_exists("error", $response))) {
                                            $message_keys['charName'] = $response['name'];
                                        } else $message_keys['charName'] = '';
                                    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
                                        $message_keys['charName'] = '';
                                    }
                                    try{ 
                                        $response = $this->eveauth->getsolar($message_keys['solarsystemID']);
                                        if(!(is_array($response)&&array_key_exists("error", $response))) {
                                            $message_keys['solarName'] = $response['name'];
                                        } else $message_keys['solarName'] = '';
                                    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
                                        $message_keys['solarName'] = '';
                                    }
                                    try{ 
                                        $response = $this->eveauth->getstructure($message_keys['structureID'],$notification->token);
                                        if(!(is_array($response)&&array_key_exists("error", $response))) {
                                            $message_keys['structureName'] = $response['name'];
                                        } else $message_keys['structureName'] = '';
                                    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
                                        $message_keys['structureName'] = '';
                                    }
                                    $message_keys['time'] = $evenotify_model->timestamp;
                                    $message_keys['type_text'] = 'Структура атакована';
                                    break;
                                case 'EntosisCaptureStarted':
                                    preg_match_all('/(\w+)(?:: &id001 |: )(\d+|\w+ \w+|\w+)/',$message,$matches);
                                    $message_keys = array_combine ( $matches[1], $matches[2] );
                                    //preg_match('/(?:solarsystemID: )(\d+)/i',$message,$matches);
                                    //$message_keys['solarsystemID'] = (!empty($matches[1]))?$matches[1]:'';
                                    $response = $this->eveauth->getsolar($message_keys['solarsystemID']);
                                    if(!(is_array($response)&&array_key_exists("error", $response))) {
                                        $message_keys['solarName'] = $response['name'];
                                    } else $message_keys['solarName'] = '';
                                    //preg_match('/(?:structureTypeID: )(\d+)/i',$message,$matches);
                                    //$message_keys['structureTypeID'] = (!empty($matches[1]))?$matches[1]:'';
                                    $response = $this->eveauth->getTypeID($message_keys['structureTypeID']);
                                    if(!(is_array($response)&&array_key_exists("error", $response))) {
                                        $message_keys['structureName'] = $response['name'];
                                    } else $message_keys['structureName'] = '';
                                    $message_keys['time'] = $evenotify_model->timestamp;
                                    $message_keys['type_text'] = 'Энтоз - начало захвата';
                                    $message_keys['charName'] = '';
                                    $message_keys['charID'] = '';
                                    $message_keys['corpName'] = '';
                                    $message_keys['allianceName'] = '';
                                    $message_keys['allianceID'] = '';
//                                    dd($message_keys);
                                    break;
                            }
                            $evenotify_model->setDiscordChannel($channel->channel_id);
    
                            $disc = new EveNotifDiscord($message_keys,$channel->mention);
                            try{
                                $r =  $evenotify_model->notify($disc);
                            } catch (\NotificationChannels\Discord\Exceptions\CouldNotSendNotification $e) {
                                dd($e);
                            }
                        }
                    }
                }
            }
        }
//        dd($evemails);
        $this->info('Got eve notif ');
    }
}
