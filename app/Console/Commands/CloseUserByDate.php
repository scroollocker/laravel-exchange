<?php

namespace App\Console\Commands;

use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CloseUserByDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bank:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close user by date';

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
        $users = User::where('active_date', '<=', Carbon::now())
            ->where('blocked', '0')
            ->where('isAdmin', '0')
            ->get();

        $this->info('Count users = '.count($users));
    }
}
