<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' =>'Brayan Mendez Colque',
            'email' => 'Super.User@gmail.com',
            'password' => bcrypt('SuperUser.'),
            'admin' => true,
            'tipo_user' => '1',
            'dep_id' => 1,
        ]);
        User::create([
            'name' =>'Admin Rolando Yucra',
            'email' => 'rolando.yucra@gmail.com',
            'password' => bcrypt('RolandoYucra*123'),
            'admin' => true,
            'tipo_user' => '2',
            'dep_id' => 1,
        ]);
        User::create([
            'name' =>'Ghunards Leon',
            'email' => 'Ghunards.Leon@Upds.edu.bo',
            'password' => bcrypt('Ghunards*123'),
            'admin' => false,
            'tipo_user' => '0',
            'dep_id' => 2,
        ]);
    }
}
