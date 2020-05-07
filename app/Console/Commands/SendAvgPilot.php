<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Notifications\EveMailDiscord;
use Spatie\Browsershot\Browsershot;

class SendAvgPilot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendavg:evemails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send avg pilot';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
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
        $this->info('Sent avg pilot');
    }
}
