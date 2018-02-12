<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateCourse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bank:course';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update bank courses';

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
            $courses = \Api::execute('getCourses', new \stdClass());

            if (!$courses['status']) {
                throw new \Exception($courses['getCourses']);
            }

            $currencies = $courses['response']['Currencyes'];

            foreach($currencies as $cur) {
                \DB::select('call merge_courses(?,?,?);', array($cur['cur'], $cur['course_sell'], $cur['course_buy']));
            }

            \Log::info('JOB :: Courses updated');
            \Log::info(count($currencies));

        }
        catch(\Exception $ex) {
            \Log::error('JOB :: Update courses error');
            \Log::error($ex);
        }
    }
}
