<?php

namespace Database\Seeders;
use App\Models\AdminModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET FOREIGN_KEY_CHECKS = 0");
        DB::table("admins")->truncate();

        $data = [
            [
                "email" => 'admin@gmail.com',
                "name" => 'admin',
                "password" => Hash::make('123456'),
                "status" => 1,
                "role" => 0,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s')
            ],
        ];
        AdminModel::insert($data);

        DB::statement("SET FOREIGN_KEY_CHECKS = 1");
    }
}
