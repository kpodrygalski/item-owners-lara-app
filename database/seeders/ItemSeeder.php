<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $user = User::first();

        for ($i = 0; $i <= 10; $i++) {
            Item::create([
                'name' => "Item " . $i,
                'quantity' => $i,
                'user_id' => $user->id
            ]);
        }
    }
}
