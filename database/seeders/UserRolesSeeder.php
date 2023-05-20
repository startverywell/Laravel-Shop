<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_roles')->delete();
        DB::table('user_roles')->insert([
            'id' => 1,
            'name' => '管理者',
        ]);
        DB::table('user_roles')->insert([
            'id' => 2,
            'name' => 'ユーザ',
        ]);

    }
}
