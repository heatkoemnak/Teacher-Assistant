<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use App\Models\RolePermission;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $roles = ['administrator','user','teacher'];
        $permissions = ['create','read','update','delete'];

        foreach($roles as $role){
            Role::factory()->create([
                'name'=>$role
            ]);
        }
        
        User::factory(5)->create();

        foreach($permissions as $permission){
            Permission::factory()->create([
                'name'=>$permission
            ]);
        }


    }
}
