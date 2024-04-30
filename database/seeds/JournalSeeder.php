<?php

use App\Client;
use App\Journal;
use Illuminate\Database\Seeder;

class JournalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clients = Client::all();

        $clients->each(function ($client) {
            Journal::factory()
                ->count(random_int(0, 5))
                ->create(['client_id' => $client->id]);
        });
    }
}
