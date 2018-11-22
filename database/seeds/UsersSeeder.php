<?php


use App\Domains\User\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $user = new User();
        $user->name = 'Developer';
        $user->email = 'developer@devsquad.com';
        $user->password = bcrypt('devsquad');
        $user->save();
    }
}