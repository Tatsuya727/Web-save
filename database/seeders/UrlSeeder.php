<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Url;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UrlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 50; $i++) {
            DB::table('urls')->insert([
                'url' => 'http://example.com/' . Str::random(10),
                'title' => Str::random(10),
                'description' => Str::random(50),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
