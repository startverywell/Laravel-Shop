<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(UserSeeder::class);
        $this->call(UserRolesSeeder::class);
        $this->call(AnswerTypesSeeder::class);
        $this->call(QuestionTypesSeeder::class);
        $this->call(SettingsSeeder::class);
        $this->call(StatusSeeder::class);
    }
}
