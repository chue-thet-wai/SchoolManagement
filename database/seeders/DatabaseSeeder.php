<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\Permission;
use Database\Seeders\RoleandPermission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(UserSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleandPermissionSeeder::class);
    }
}
