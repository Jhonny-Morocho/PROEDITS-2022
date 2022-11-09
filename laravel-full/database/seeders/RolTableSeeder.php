<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   //spetter documentacion https://spatie.be/docs/laravel-permission/v3/introduction
        //documentacion https://spatie.be/docs/laravel-permission/v5/advanced-usage/seeding
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'edit products']);
        Permission::create(['name' => 'delete products']);
        Permission::create(['name' => 'publish products']);
        Permission::create(['name' => 'unpublish products']);


        // create roles and assign created permissions
        // this can be done as separate statements
        $role = Role::create(['name' => 'escritor']);
        $role->givePermissionTo('edit products');

        // or may be done by chaining
        $role = Role::create(['name' => 'moderador'])
            ->givePermissionTo(['publish products', 'unpublish products']);

        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());

        //cliente
        $role = Role::create(['name' => 'cliente']);
        Permission::create(['name' => 'comprar']);
        $role->givePermissionTo(Permission::all());

    }
}
