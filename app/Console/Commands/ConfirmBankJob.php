<?php

namespace App\Console\Commands;

use App\Invoice;
use App\Offer;
use Illuminate\Console\Command;

class ConfirmBankJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bank:confirm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Confirm bank deals';

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
    public function handle() {
        try {
            $offers = Offer::whereHas('state', function ($q) {
                $q->where('IN_BANK');
            })->with('detail', 'origin')->get();

            foreach ($offers as $offer) {
                $params = array(
                    'Deal' => $offer->declare_id
                );


                $result = \Api::execute('getDealState', $params);

                if ($result['status'] == false) {
                    throw new \Exception('Ошибка АБС: ' . $result['message']);
                }

                $msg = trim(strtolower($result['response']['msg']));

                if ($msg == null or ($msg != 'ok' and $msg != 'work')) {

                    DB::select('call exec_offer_refuse_bank(?)', array($offer->detail_id));
                    DB::select('call exec_declare_refuse_bank(?)', array($offer->declare_id));

                }
                else if ($msg == 'ok') {

                }
            }

        }
        catch(\Exception $ex) {
            \Log::error('Job error');
            \Log::error($ex);
        }

    }
}
