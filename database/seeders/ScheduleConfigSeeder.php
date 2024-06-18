<?php

namespace Database\Seeders;

use App\Models\ScheduleConfig;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ScheduleConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('schedule_config')->truncate();

        ScheduleConfig::create([
            'status' => 'on',
            'time' => 60,
            'lastime' => now(),
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
