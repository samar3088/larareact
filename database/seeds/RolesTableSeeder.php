<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
         'name'  => 'Admin',
         'slug' => 'admin',
         'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
         'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        DB::table('roles')->insert([
         'name'  => 'Author',
         'slug' => 'author',
         'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
         'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        DB::table('roles')->insert([
            'name'  => 'Business',
            'slug' => 'business',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
           ]);
    }
}
