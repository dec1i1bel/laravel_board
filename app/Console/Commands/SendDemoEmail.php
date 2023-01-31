<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendDemoEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sends demo email';

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
     * @return int
     */
    public function handle()
    {
        $this->info('email is being sent...');

        mail(
            'v.balabanov@ddemo.ru',
            'тест отправки из консоли',
            'hello from console',
        );

        $this->comment('email sent');
        
    }
}
