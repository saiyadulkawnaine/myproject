<?php

use App\User;
use jeremykenedy\LaravelRoles\Models\Role;
use jeremykenedy\LaravelRoles\Models\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $supAdminRole 			= Role::where('name', '=', 'Supper Administrator')->first();
		$adminRole 			    = Role::where('name', '=', 'Adminitrator')->first();
		$permissions 		    = Permission::all();

	    /**
	     * Add Users
	     *
	     */
        if (User::where('email', '=', 'supadmin@admin.com')->first() === null) {

	        $newUser = User::create([
	            'name' => 'Super Admin',
	            'email' => 'supadmin@admin.com',
	            'password' => bcrypt('123456'),
	        ]);

	        $newUser->attachRole($supAdminRole);
			foreach ($permissions as $permission) {
				$newUser->attachPermission($permission);
			}

        }

        if (User::where('email', '=', 'admin@admin.com')->first() === null) {

	        $newUser = User::create([
	            'name' => 'Admin',
	            'email' => 'admin@admin.com',
	            'password' => bcrypt('123456'),
	        ]);

	        $newUser;
	        $newUser->attachRole($adminRole);

        }

    }
}