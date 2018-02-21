<?php

namespace App\Console\Commands;

use App\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SenderJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bank:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification command';

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

        $notifications = Notification::select('msg_id', 'msg_v', 'receiver_v', 'event_n')
                        ->get();

        if (count($notifications) > 0) {
            foreach($notifications as $item) {
                if ($item->event_n == 2) {
                    \Sms::sendSms($item->receiver_v, $item->msg_v);
                    Notification::checkSended($item->msg_id);
                }
                else if ($item->event_n == 1) {
                    Mail::raw($item->msg_v, function ($message) use ($item) {
                        $message->subject('Уведомления с сайта');
                        $message->from(env('MAIL_USERNAME'));

                        $message->to($item->receiver_v);
                    });
                    Notification::checkSended($item->msg_id);
                }
            }
        }

    }
}
