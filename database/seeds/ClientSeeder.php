<?php

use App\User;
use App\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::firstOrCreate([
            'name' => 'Super Admin',
            'email' => 'user@example.test',
            'password' => Hash::make('password')
        ]);

        factory(Client::class, 10)->create([
            'user_id' => $user->id,
        ]);

        factory(Client::class, 150)->create();
    }
}
