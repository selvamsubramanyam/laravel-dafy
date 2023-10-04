<?php

use Illuminate\Database\Seeder;
use Modules\Admin\Entities\AdminUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Modules\Users\Entities\Role;
use Modules\Users\Entities\Permission;
use Modules\Users\Entities\Rolepermission;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
       
        $name = $this->command->ask('What is the super admin name?');
        $mobile = $this->command->ask('What is the super admin mobile number?');
        $email = $this->command->ask('What is the super admin email address?');
        $password = $this->command->secret('What is the super admin password?');

        $role = Role::where('name','Super Admin')->first();

        AdminUser::updateOrCreate([
            'role'   => $role->id,
        ],[
            'name' => $name,
            'mobile' => $mobile,
            'email' => $email,
            'password' => Hash::make($password)
        ]);

        Rolepermission::query()->truncate();
        $permission = Permission::all();
        $role->permissions()->sync($permission);

        

        $this->command->info("Super Admin $name  was created");
    }
}
