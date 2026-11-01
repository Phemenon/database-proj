<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        //summon demo user
        $user_exist = User::where('name', 'Test User')->where('email', 'test@example.com')->exists();
        
        if ($user_exist)
            $this->command?->info('Test User Already exists');
        else 
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        //seed db with demo item
        $this->call([
            FrontendDemoSeeder::class,
        ]);
    }
}
