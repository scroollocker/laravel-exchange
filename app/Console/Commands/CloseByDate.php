<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CloseByDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bank:date';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close declares by date';

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
            DB::select('call close_declares();');
        }
        catch(\Exception $ex) {
            \Log::error('JOB :: Close by date error');
            \Log::error($ex);
        }
    }
}
