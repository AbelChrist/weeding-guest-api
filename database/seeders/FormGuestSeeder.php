<?php

namespace Database\Seeders;

use App\Models\FormGuest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormGuestSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FormGuest::factory()->count(20)->create();
    }
}
