<?php

use App\User;
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
        $user->isAdmin = true;

        $user->save();

    }
}
