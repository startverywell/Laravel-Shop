<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnswerTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('answer_types')->delete();
        DB::table('answer_types')->insert([
            'id' => 1,
            'name' => 'テキスト',
        ]);
        DB::table('answer_types')->insert([
            'id' => 2,
            'name' => '画像',
        ]);
        DB::table('answer_types')->insert([
            'id' => 3,
            'name' => '集団',
        ]);
    }
}
