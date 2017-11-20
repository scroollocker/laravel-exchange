<?php

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;

        $user->name = 'Scroollocker';
        $user->password = bcrypt('test');
        $user->email = 'arsenal_30@mail.ru';
        $user->phone = '996555905844';
        $user->ibs_id = 1;
        $user->invoice_count = 100;
        $user->active_date = Carbon::now();
        $user->comment = '';
        $user->isAdmin = true;

        $user->save();

        $user = new User;

        $user->name = 'Scroollocker User';
        $user->password = bcrypt('test');
        $user->email = 'scroollocker@mail.ru';
        $user->phone = '996555905844';
        $user->ibs_id = 2;
        $user->invoice_count = 50;
        $user->active_date = Carbon::now();
        $user->comment = '';
        $user->isAdmin = false;

        $user->save();

    }
}
