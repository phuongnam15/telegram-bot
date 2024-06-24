<?php

namespace Database\Seeders;

use App\Models\ScheduleDeleteMessage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ScheduleDeleteMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('schedule_delete_message')->truncate();

        ScheduleDeleteMessage::create([
            'delay_time' => 1
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
