<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PasswordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('passwords')->truncate();
        
        $arrayPass = [
            '2EoBhsV0', 'mpm40yL2', 'K1cb8ClT', 'NKajAdzz', '8CdGfMZZ', 'KFUrW0NB',
            'sF0V96aR', 'kxiu7DBD', 'QWcm0yR8', '4g8VizU4', 'dlWLQH2T', 'bs0fnoyl',
            'ltE8drdK', 'mgZIYw0N', 'DENq9cgr', 'NxGjAwLI', 'EjRh2qG5', 'PC96gSHw',
            'YP58iv4Y', '93WIbBAz', '1M0dn7ol', 'TdY0WTXW', 'xda8NirC', 'jBU3jR8f',
            'uIgTUIDv', 'ntPHsloN', 'xGsb4YeP', 'Xd0V4POS', '7mWUPRrB', 'FCbRnw5l',
            'WRrwAYk0', 'rIvzf1w2', '7bZT6dJ5', 't3vWPwai', 'VP8v4xpv', 'UDPBL4UL',
            'CJ77rcvI', 'C8HrV0qs', 'abTANkrZ', '397IYYev', 'pT99ewM1', '150Eha2n',
            'bjVWgCIB', 'hJwJJf6m', 'eXS974TV', '8YuXdMYl', 'IApR69hE', 'pCpUjC08',
            '0bdDwMhN', '3pVo5mtG', 'pmI5jrqU', 'fwYwnUg7', 'RC74QLuF', 'BhTBud28',
            'W0rKeh3X', 'WE7pznyf', 'gnzJPwGc', 'UG2QfGeU', 'dgzkR1A4', 'rqtp3JBC',
            'ebFmklmm', '6AsQVo5h', 'Lvtb6g4e', 'PnXAoPfC', 'njFzknQW', 'lxKMAgl7',
            'rP5YrGzz', 'yqUtO0GA', 'GgeVsRwi', 'bpG1USFq', 'nb6fecPu', 'KaxgaTpo',
            'Nyh4TfUz', 'ngLSsBI2', 'zsgf7YLI', 'SETB9Hqe', 'MWy3aL0a', 'RMrpnAzY',
            'qehWG0Qs', '1kX11mdJ', 'glrtXrJg', '5oGcrvJF', 'a8akQY5G', 'gIZiCh1P',
            'XnEM8bGa', '1TSDzLpT', 'uTmjzeRt', 'hHi8uXqr', 'shtrAHdk', 'fSiYkn98',
            'xTLO6mnx', 'gSFh3yXs', 'A0j9oO2V', 'ccSwUBjf', 'FQ67w7wZ', 'G5SM6iTq',
            'udgJTbWY', 'znntC6WS', '38QIw39s', 'GJ4izaNm'
        ];

        foreach ($arrayPass as $pass) {
            DB::table('passwords')->insert([
                'password' => $pass
            ]);
        }

        Schema::enableForeignKeyConstraints();
    }
}
