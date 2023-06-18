<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Url;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;


class UrlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 50; $i++) {
            $randomDate = \Carbon\Carbon::now()->subDays(rand(0, 365));
            DB::table('urls')->insert([
                'url' => 'http://example.com/' . Str::random(10),
                'title' => Str::random(10),
                'description' => Str::random(50),
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ]);
        }
        
    }
}
