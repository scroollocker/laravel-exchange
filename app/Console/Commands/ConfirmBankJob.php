<?php

namespace App\Console\Commands;

use App\Invoice;
use App\Notification;
use App\Offer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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

    private function senderJob($declare_id, $detail_id) {
        $notifications = Notification::select('msg_id', 'msg_v', 'receiver_v', 'event_n')
            ->where('declare_id', $declare_id)
            ->orWhere('declare_id', $detail_id)
            ->get();

        if (count($notifications) > 0) {
            foreach($notifications as $item) {
                if ($item->event_n == 2) {
                    \Sms::sendSms($item->receiver_v, $item->msg_v);
                    Notification::checkSended($item->msg_id);
                }
                else if ($item->event_n == 1) {
                    Mail::send('custom.mail', array('msg' => $item->msg_v), function ($message) use ($item) {
                        $message->subject('Уведомления с сайта');
                        $message->from(env('MAIL_USERNAME'));

                        $message->to($item->receiver_v);
                    });
                    Notification::checkSended($item->msg_id);
                }
            }
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        try {
            $offers = Offer::whereHas('state', function ($q) {
                $q->where('code_v','IN_BANK');
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

                    DB::select('call exec_offer_refuse_bank(?)', array($offer->details_id));
                    DB::select('call exec_declare_refuse_bank(?)', array($offer->declare_id));

                }
                else if ($msg == 'ok') {
                    DB::select('call exec_offer_close(?)', array($offer->offer_id));
                    DB::select('call exec_declare_close(?)', array($offer->declare_id));
                    $this->senderJob($offer->declare_id, $offer->details_id);
                }
            }

        }
        catch(\Exception $ex) {
            \Log::error('JOB :: Close by state error');
            \Log::error($ex);
        }

    }
}
