<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\EveAuthController;
use App\Models\Evemail;
use App\Notifications\EveMailDiscord;
use NotificationChannels\Discord\Exceptions\CouldNotSendNotification;

class SendEveMentorMails extends Command
{
    protected $eveauth;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:evemails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sent eve mails';

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
        $evemails = $this->eveauth->sendevemail();
        $this->info('Sent eve mails ');
        
    }
}
