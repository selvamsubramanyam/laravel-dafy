<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Modules\Users\Entities\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Model::unguard();

        Role::updateOrCreate([
            'name'   => 'Super Admin',
        ],[
            'description' => 'admin user',
            'slug' => 'super-admin'
        ]);

        $this->command->info("Super Admin created");

        Role::updateOrCreate([
            'name'   => 'Seller',
        ],[
            'description' => 'seller',
            'slug' => 'seller'
        ]);

        $this->command->info("Seller created");

        Role::updateOrCreate([
            'name'   => 'Customer',
        ],[
            'description' => 'customer',
            'slug' => 'customer'
        ]);

        $this->command->info("Customer created");

        Role::updateOrCreate([
            'name'   => 'Sub Admin',
        ],[
            'description' => 'subadmin',
            'slug' => 'sub-admin'
        ]);

        $this->command->info("Sub Admin created");
        
    }
}
