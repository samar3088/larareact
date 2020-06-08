<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
         'role_id'  => '1',
         'name' => 'Kazi',
         'username' => 'admin',
         'email' => 'admin@gmail.com',
         'password' => bcrypt('rootadmin'),
         'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
         'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);


        DB::table('users')->insert([
         'role_id'  => '2',
         'name' => 'Ariyan',
         'username' => 'author',
         'email' => 'author@gmail.com',
         'password' => bcrypt('rootauthor'),
         'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
         'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
