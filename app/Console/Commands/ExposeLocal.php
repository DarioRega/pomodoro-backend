<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExposeLocal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expose';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expose your localhost using laravel-sail.site and your name';

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
     */
    public function handle()
    {
        $name = $this->choice('what is your name?', ['dario', 'renato']);
        $this->info(
            "You need to go to this website: https://dashboard.pusher.com/apps/1217962/webhooks " .
            "and activate the webhook with your name"
        );
        $this->newLine();
        $this->warn('Do not forget to deactivate it when you are finish');
        system("./vendor/bin/sail share --subdomain=$name.pomodoro");
    }
}
