<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$OrZCwXxYzXBUNN28ETQZwOyayLzed.mnrBfOAkOkcwD8ss0Ur1ycC',
                'remember_token' => 'UhLYJiY0ikijFewLwnbemwMpkogCXqiTQ8wzPk6d5KuAuSYkdQMHpLHU135b',
                'created_at' => '2023-11-24 10:38:41',
                'updated_at' => '2023-11-24 10:38:41',
                'permissions' => '{"platform.index": true, "platform.systems.roles": true, "platform.systems.users": true, "platform.systems.attachment": true}',
            ),
        ));
        
        
    }
}