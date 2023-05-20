<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statuses')->delete();
        DB::table('statuses')->insert([
            'id' => 1,
            'name' => '下書き',
        ]);
        DB::table('statuses')->insert([
            'id' => 2,
            'name' => '非公開',
        ]);
        DB::table('statuses')->insert([
            'id' => 3,
            'name' => '公開',
        ]);
    }
}
