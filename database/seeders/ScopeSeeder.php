<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ScopeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('scopes')->insert([
            'name' => 'View Users',
            'scope_key' => 'user_model',
            'scope_bit' => 1,
            'created_at' => Date::now(),
            'updated_at' => Date::now()
        ]);
    }
}
