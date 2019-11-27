<?php


use App\Domains\User\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $user = new User();
        $user->name = 'Developer';
        $user->email = 'developer@test.com';
        $user->password = bcrypt('test@00');
        $user->save();
    }
}
