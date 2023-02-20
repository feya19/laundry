<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [[
            'name' => 'Admin',
            'username' => 'admin',
            'role' => 'admin',
            'password' => bcrypt('admin123'),
            'created_at' => now()
        ],[
            'name' => 'Kasir',
            'username' => 'kasir',
            'role' => 'kasir',
            'password' => bcrypt('kasir123'),
            'created_at' => now()
        ],[
            'name' => 'Owner',
            'username' => 'owner',
            'role' => 'owner',
            'password' => bcrypt('owner123'),
            'created_at' => now()
        ]];
        DB::beginTransaction();
        try{
            User::insert($users);
            DB::commit();
            echo 'User Seeded';
        }catch (\Exception $e){
            DB::rollback();
            echo 'Failed: '.$e->getMessage();
        }
    }
}
