<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        DB::table('users')->insert([
            'id' => 1,
            'name' => '管理者',
            'full_name' => '管理者', 
            'email' => 'admin@admin.com', 
            'password' => bcrypt('password'), 
            'role_id' => 1
        ]);
    }
}
