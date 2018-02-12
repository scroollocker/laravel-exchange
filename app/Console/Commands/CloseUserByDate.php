<?php

namespace App\Console\Commands;

use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
        try {
            DB::select('call auto_lock_users();');
        }
        catch(\Exception $ex) {
            \Log::error('JOB :: Close by date error');
            \Log::error($ex);
        }
    }
}
