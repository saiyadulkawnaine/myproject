<?php

use App\User;
use jeremykenedy\LaravelRoles\Models\Role;
use jeremykenedy\LaravelRoles\Models\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

	    /**
	     * Add Permissions
	     *
	     */
		 if (Permission::where('name', '=', 'Can View Menus')->first() === null) {
			Permission::create([
			    'name' => 'Can View Menus',
			    'slug' => 'view.menus',
			    'description' => 'Can view menus',
			    'model' => 'Menu',
			]);
        }

        if (Permission::where('name', '=', 'Can Create Menus')->first() === null) {
			Permission::create([
			    'name' => 'Can Create Menus',
			    'slug' => 'create.menus',
			    'description' => 'Can create new menus',
			    'model' => 'Menu',
			]);
        }

        if (Permission::where('name', '=', 'Can Edit Menus')->first() === null) {
			Permission::create([
			    'name' => 'Can Edit Menus',
			    'slug' => 'edit.menus',
			    'description' => 'Can edit menus',
			    'model' => 'Menu',
			]);
        }

        if (Permission::where('name', '=', 'Can Delete Menus')->first() === null) {
			Permission::create([
			    'name' => 'Can Delete Menus',
			    'slug' => 'delete.menus',
			    'description' => 'Can delete menus',
			    'model' => 'Menu',
			]);
        }
		
		if (Permission::where('name', '=', 'Can Approve Menus')->first() === null) {
			Permission::create([
			    'name' => 'Can Approve Menus',
			    'slug' => 'approve.menus',
			    'description' => 'Can approve menus',
			    'model' => 'Menu',
			]);
        }
		
		
		if (Permission::where('name', '=', 'Can View Permissions')->first() === null) {
			Permission::create([
			    'name' => 'Can View Permissions',
			    'slug' => 'view.permissions',
			    'description' => 'Can view permissions',
			    'model' => 'Permission',
			]);
        }

        if (Permission::where('name', '=', 'Can Create Permissions')->first() === null) {
			Permission::create([
			    'name' => 'Can Create Permissions',
			    'slug' => 'create.permissions',
			    'description' => 'Can create new permissions',
			    'model' => 'Permission',
			]);
        }

        if (Permission::where('name', '=', 'Can Edit Permissions')->first() === null) {
			Permission::create([
			    'name' => 'Can Edit Permissions',
			    'slug' => 'edit.permissions',
			    'description' => 'Can edit permissions',
			    'model' => 'Permission',
			]);
        }

        if (Permission::where('name', '=', 'Can Delete Permissions')->first() === null) {
			Permission::create([
			    'name' => 'Can Delete Permissions',
			    'slug' => 'delete.permissions',
			    'description' => 'Can delete permissions',
			    'model' => 'Permission',
			]);
        }
		
		if (Permission::where('name', '=', 'Can Approve Permissions')->first() === null) {
			Permission::create([
			    'name' => 'Can Approve Permissions',
			    'slug' => 'approve.permissions',
			    'description' => 'Can approve permissions',
			    'model' => 'Permission',
			]);
        }
		
		
		 if (Permission::where('name', '=', 'Can View Roles')->first() === null) {
			Permission::create([
			    'name' => 'Can View Roles',
			    'slug' => 'view.roles',
			    'description' => 'Can view roles',
			    'model' => 'Role',
			]);
        }

        if (Permission::where('name', '=', 'Can Create Roles')->first() === null) {
			Permission::create([
			    'name' => 'Can Create Roles',
			    'slug' => 'create.roles',
			    'description' => 'Can create new roles',
			    'model' => 'Role',
			]);
        }

        if (Permission::where('name', '=', 'Can Edit Roles')->first() === null) {
			Permission::create([
			    'name' => 'Can Edit Roles',
			    'slug' => 'edit.roles',
			    'description' => 'Can edit roles',
			    'model' => 'Role',
			]);
        }

        if (Permission::where('name', '=', 'Can Delete Roles')->first() === null) {
			Permission::create([
			    'name' => 'Can Delete Roles',
			    'slug' => 'delete.roles',
			    'description' => 'Can delete roles',
			    'model' => 'Role',
			]);
        }
		
		if (Permission::where('name', '=', 'Can Approve Roles')->first() === null) {
			Permission::create([
			    'name' => 'Can Approve Roles',
			    'slug' => 'approve.roles',
			    'description' => 'Can approve roles',
			    'model' => 'Role',
			]);
        }
		
        if (Permission::where('name', '=', 'Can View Users')->first() === null) {
			Permission::create([
			    'name' => 'Can View Users',
			    'slug' => 'view.users',
			    'description' => 'Can view users',
			    'model' => 'User',
			]);
        }

        if (Permission::where('name', '=', 'Can Create Users')->first() === null) {
			Permission::create([
			    'name' => 'Can Create Users',
			    'slug' => 'create.users',
			    'description' => 'Can create new users',
			    'model' => 'User',
			]);
        }

        if (Permission::where('name', '=', 'Can Edit Users')->first() === null) {
			Permission::create([
			    'name' => 'Can Edit Users',
			    'slug' => 'edit.users',
			    'description' => 'Can edit users',
			    'model' => 'User',
			]);
        }

        if (Permission::where('name', '=', 'Can Delete Users')->first() === null) {
			Permission::create([
			    'name' => 'Can Delete Users',
			    'slug' => 'delete.users',
			    'description' => 'Can delete users',
			    'model' => 'User',
			]);
        }
		
		if (Permission::where('name', '=', 'Can Approve Users')->first() === null) {
			Permission::create([
			    'name' => 'Can Approve Users',
			    'slug' => 'approve.users',
			    'description' => 'Can approve users',
			    'model' => 'User',
			]);
        }
    }
}
