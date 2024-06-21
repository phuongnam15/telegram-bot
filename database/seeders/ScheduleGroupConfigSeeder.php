<?php

namespace Database\Seeders;

use App\Models\ScheduleGroupConfig;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ScheduleGroupConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('schedule_group_config')->truncate();

        ScheduleGroupConfig::create([
            'status' => 'on',
            'time' => 60,
            'lastime' => now(),
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
