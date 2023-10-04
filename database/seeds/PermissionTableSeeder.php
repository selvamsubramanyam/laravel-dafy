<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Users\Entities\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Model::unguard();

        Permission::updateOrCreate([
            'slug'   => 'dashboard',
        ],[
            'title'        => 'Dashboard',
            'description' => 'Dashboard'
        ]);
        $this->command->info("Dashboard created");

        Permission::updateOrCreate([
            'slug'   => 'role-list',
        ],[
            'title'        => 'List Role',
            'description' => 'List Role'
        ]);
        $this->command->info("List Role created");

        Permission::updateOrCreate([
            'slug'   => 'role-create',
        ],[
            'title'        => 'Add Role',
            'description' => 'Add Role'
        ]);
        $this->command->info("Add Role created");

        Permission::updateOrCreate([
            'slug'   => 'role-edit',
        ],[
            'title'        => 'Edit Role',
            'description' => 'Edit Role'
        ]);
        $this->command->info("Edit Role created");

        
        Permission::updateOrCreate([
            'slug'   => 'role-delete',
        ],[
            'title'        => 'Delete Role',
            'description' => 'Delete Role'
        ]);
        $this->command->info("Delete Role created");

        Permission::updateOrCreate([
            'slug'   => 'admin_user-list',
        ],[
            'title'        => 'List Admin User',
            'description' => 'List Admin User'
        ]);
        $this->command->info("List Admin User created");

        Permission::updateOrCreate([
            'slug'   => 'admin_user-add',
        ],[
            'title'        => 'Add Admin User',
            'description' => 'Add Admin User'
        ]);
        $this->command->info("Add Admin User created");

        Permission::updateOrCreate([
            'slug'   => 'admin_user-edit',
        ],[
            'title'        => 'Edit Admin User',
            'description' => 'Edit Admin User'
        ]);
        $this->command->info("Edit Admin User created");

        Permission::updateOrCreate([
            'slug'   => 'admin_user-delete',
        ],[
            'title'        => 'Delete Admin User',
            'description' => 'Delete Admin User'
        ]);
        $this->command->info("Delete Admin User created");

        Permission::updateOrCreate([
            'slug'   => 'category-list',
        ],[
            'title'        => 'List Category',
            'description' => 'List Category'
        ]);
        $this->command->info("List Category created");
        
        Permission::updateOrCreate([
            'slug'   => 'category-create',
        ],[
            'title'        => 'Add Category',
            'description' => 'Add Category'
        ]);
        $this->command->info("Add Category created");

        
        Permission::updateOrCreate([
            'slug'   => 'category-edit',
        ],[
            'title'        => 'Edit Category',
            'description' => 'Edit Category'
        ]);
        $this->command->info("Edit Category created");

        Permission::updateOrCreate([
            'slug'   => 'category-delete',
        ],[
            'title'        => 'Delete Category',
            'description' => 'Delete Category'
        ]);
        $this->command->info("Delete Category created");

        Permission::updateOrCreate([
            'slug'   => 'attribute-list',
        ],[
            'title'        => 'List Attributes',
            'description' => 'List Attributes'
        ]);
        $this->command->info("List Attributes created");

        Permission::updateOrCreate([
            'slug'   => 'attribute-create',
        ],[
            'title'        => 'Add  Attributes',
            'description' => 'Add  Attributes'
        ]);
        $this->command->info("Add  Attributes created");

        Permission::updateOrCreate([
            'slug'   => 'attribute-edit',
        ],[
            'title'        => 'Edit  Attributes',
            'description' => 'Edit  Attributes'
        ]);
        $this->command->info("Edit  Attributes created");

        Permission::updateOrCreate([
            'slug'   => 'attribute-delete',
        ],[
            'title'        => 'Delete Attributes',
            'description' => 'Delete  Attributes'
        ]);
        $this->command->info("Delete  Attributes created");

        Permission::updateOrCreate([
            'slug'   => 'banner-list',
        ],[
            'title'        => 'List Banner',
            'description' => 'List Banner'
        ]);
        $this->command->info("List Banner created");

        
        Permission::updateOrCreate([
            'slug'   => 'banner-create',
        ],[
            'title'        => 'Add Banner',
            'description' => 'Add Banner'
        ]);
        $this->command->info("Add Banner created");

        Permission::updateOrCreate([
            'slug'   => 'banner-edit',
        ],[
            'title'        => 'Edit Banner',
            'description' => 'Edit Banner'
        ]);
        $this->command->info("Edit Banner created");

        Permission::updateOrCreate([
            'slug'   => 'banner-delete',
        ],[
            'title'        => 'Delete Banner',
            'description' => 'Delete Banner'
        ]);
        $this->command->info("Delete Banner created");

        Permission::updateOrCreate([
            'slug'   => 'state-list',
        ],[
            'title'        => 'List State',
            'description' => 'List State'
        ]);
        $this->command->info("List State created");

        Permission::updateOrCreate([
            'slug'   => 'state-create',
        ],[
            'title'        => 'Add State',
            'description' => 'Add State'
        ]);
        $this->command->info("Add State created");

        Permission::updateOrCreate([
            'slug'   => 'state-edit',
        ],[
            'title'        => 'Edit State',
            'description' => 'Edit State'
        ]);
        $this->command->info("Edit State created");

        Permission::updateOrCreate([
            'slug'   => 'state-delete',
        ],[
            'title'        => 'Delete State',
            'description' => 'Delete State'
        ]);
        $this->command->info("Delete State created");

        Permission::updateOrCreate([
            'slug'   => 'city-list',
        ],[
            'title'        => 'List City',
            'description' => 'List City'
        ]);
        $this->command->info("List City created");

        Permission::updateOrCreate([
            'slug'   => 'city-create',
        ],[
            'title'        => 'Add City',
            'description' => 'Add City'
        ]);
        $this->command->info("Add City created");

        Permission::updateOrCreate([
            'slug'   => 'city-edit',
        ],[
            'title'        => 'Edit City',
            'description' => 'Edit City'
        ]);
        $this->command->info("Edit City created");

        Permission::updateOrCreate([
            'slug'   => 'city-delete',
        ],[
            'title'        => 'Delete City',
            'description' => 'Delete City'
        ]);
        $this->command->info("Delete City created");

        Permission::updateOrCreate([
            'slug'   => 'product-list',
        ],[
            'title'        => 'List Product',
            'description' => 'List Product'
        ]);
        $this->command->info("List Product created");

        Permission::updateOrCreate([
            'slug'   => 'product-create',
        ],[
            'title'        => 'Add Product',
            'description' => 'Add Product'
        ]);
        $this->command->info("Add Product created");

        Permission::updateOrCreate([
            'slug'   => 'product-edit',
        ],[
            'title'        => 'Edit Product',
            'description' => 'Edit Product'
        ]);
        $this->command->info("Edit Product created");

        Permission::updateOrCreate([
            'slug'   => 'product-delete',
        ],[
            'title'        => 'Delete Product',
            'description' => 'Delete Product'
        ]);
        $this->command->info("Delete Product created");

        Permission::updateOrCreate([
            'slug'   => 'shop-list',
        ],[
            'title'        => 'List Shop',
            'description' => 'List Shop'
        ]);
        $this->command->info("List Shop created");

        Permission::updateOrCreate([
            'slug'   => 'shop-create',
        ],[
            'title'        => 'Add Shop',
            'description' => 'Add Shop'
        ]);
        $this->command->info("Add Shop created");

        Permission::updateOrCreate([
            'slug'   => 'shop-edit',
        ],[
            'title'        => 'Edit Shop',
            'description' => 'Edit Shop'
        ]);
        $this->command->info("Edit Shop created");

        Permission::updateOrCreate([
            'slug'   => 'shop-delete',
        ],[
            'title'        => 'Delete Shop',
            'description' => 'Delete Shop'
        ]);
        $this->command->info("Delete Shop created");

        Permission::updateOrCreate([
            'slug'   => 'brand-list',
        ],[
            'title'        => 'List Brand',
            'description' => 'List Brand'
        ]);
        $this->command->info("List Brand created");

        Permission::updateOrCreate([
            'slug'   => 'brand-create',
        ],[
            'title'        => 'Add Brand',
            'description' => 'Add Brand'
        ]);
        $this->command->info("Add Brand created");

        Permission::updateOrCreate([
            'slug'   => 'brand-edit',
        ],[
            'title'        => 'Edit Brand',
            'description' => 'Edit Brand'
        ]);
        $this->command->info("Edit Brand created");

        Permission::updateOrCreate([
            'slug'   => 'brand-delete',
        ],[
            'title'        => 'Delete Brand',
            'description' => 'Delete Brand'
        ]);
        $this->command->info("Delete Brand created");

        Permission::updateOrCreate([
            'slug'   => 'unit-list',
        ],[
            'title'        => 'List Unit',
            'description' => 'List Unit'
        ]);
        $this->command->info("List Unit created");

        Permission::updateOrCreate([
            'slug'   => 'unit-create',
        ],[
            'title'        => 'Add Unit',
            'description' => 'Add Unit'
        ]);
        $this->command->info("Add Unit created");

        Permission::updateOrCreate([
            'slug'   => 'unit-edit',
        ],[
            'title'        => 'Edit Unit',
            'description' => 'Edit Unit'
        ]);
        $this->command->info("Edit Unit created");

        Permission::updateOrCreate([
            'slug'   => 'unit-delete',
        ],[
            'title'        => 'Delete Unit',
            'description' => 'Delete Unit'
        ]);
        $this->command->info("Delete Unit created");

        Permission::updateOrCreate([
            'slug'   => 'seller-list',
        ],[
            'title'        => 'List Seller',
            'description' => 'List Seller'
        ]);
        $this->command->info("List Seller created");

        Permission::updateOrCreate([
            'slug'   => 'seller-create',
        ],[
            'title'        => 'Add Seller',
            'description' => 'Add Seller'
        ]);
        $this->command->info("Add Seller created");

        Permission::updateOrCreate([
            'slug'   => 'seller-edit',
        ],[
            'title'        => 'Edit Seller',
            'description' => 'Edit Seller'
        ]);
        $this->command->info("Edit Seller created");

        Permission::updateOrCreate([
            'slug'   => 'seller-delete',
        ],[
            'title'        => 'Delete Seller',
            'description' => 'Delete Seller'
        ]);
        $this->command->info("Delete Seller created");

        Permission::updateOrCreate([
            'slug'   => 'customer-list',
        ],[
            'title'        => 'List Customer',
            'description' => 'List Customer'
        ]);
        $this->command->info("List Customer created");

        Permission::updateOrCreate([
            'slug'   => 'customer-create',
        ],[
            'title'        => 'Add Customer',
            'description' => 'Add Customer'
        ]);
        $this->command->info("Add Customer created");

        Permission::updateOrCreate([
            'slug'   => 'customer-edit',
        ],[
            'title'        => 'Edit Customer',
            'description' => 'Edit Customer'
        ]);
        $this->command->info("Edit Customer created");

        Permission::updateOrCreate([
            'slug'   => 'customer-delete',
        ],[
            'title'        => 'Delete Customer',
            'description' => 'Delete Customer'
        ]);
        $this->command->info("Delete Customer created");

        
        Permission::updateOrCreate([
            'slug'   => 'offer-list',
        ],[
            'title'        => 'List Offer',
            'description' => 'List Offer'
        ]);
        $this->command->info("List Offer created");

        Permission::updateOrCreate([
            'slug'   => 'offer-create',
        ],[
            'title'        => 'Add offer',
            'description' => 'Add offer'
        ]);
        $this->command->info("Add offer created");

        Permission::updateOrCreate([
            'slug'   => 'offer-edit',
        ],[
            'title'        => 'Edit Offer',
            'description' => 'Edit Offer'
        ]);
        $this->command->info("Edit Offer created");

        Permission::updateOrCreate([
            'slug'   => 'offer-delete',
        ],[
            'title'        => 'Delete Offer',
            'description' => 'Delete Offer'
        ]);
        $this->command->info("Delete Offer created");


        Permission::updateOrCreate([
            'slug'   => 'settings',
        ],[
            'title'        => 'Settings',
            'description' => 'Settings'
        ]);
        $this->command->info("Settings created");
        
        Permission::updateOrCreate([
            'slug'   => 'app-settings',
        ],[
            'title'        => 'App Settings',
            'description' => 'App Settings'
        ]);
        $this->command->info("App Settings created");


        Permission::updateOrCreate([
            'slug'   => 'order-list',
        ],[
            'title'        => 'List Order',
            'description' => 'List Order'
        ]);
        $this->command->info("List Order created");

        Permission::updateOrCreate([
            'slug'   => 'order-view',
        ],[
            'title'        => 'View Order',
            'description' => 'View Order'
        ]);
        $this->command->info("View Order created");

        Permission::updateOrCreate([
            'slug'   => 'order-edit',
        ],[
            'title'        => 'Edit Order',
            'description' => 'Edit Order'
        ]);
        $this->command->info("Edit Order created");

        Permission::updateOrCreate([
            'slug'   => 'order-delete',
        ],[
            'title'        => 'Delete Order',
            'description' => 'Delete Order'
        ]);
        $this->command->info("Delete Order created");
    }
}
