<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'over_name' => '佐藤',
                'under_name' => '優子',
                'over_name_kana' => 'サトウ',
                'under_name_kana' => 'ユウコ',
                'mail_address' => 'yukosato@gmail.com',
                'sex' => '2',
                'birth_day' => '2000-04-04',
                'role' => '4',
                'password' => bcrypt('password')
            ]
        ]);
    }
}
