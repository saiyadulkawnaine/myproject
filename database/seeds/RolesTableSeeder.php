<?php

use App\User;
use jeremykenedy\LaravelRoles\Models\Role;
use jeremykenedy\LaravelRoles\Models\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    /**
	     * Add Roles
	     *
	     */
    	if (Role::where('name', '=', 'Supper Administrator')->first() === null) {
	        $adminRole = Role::create([
	            'name' => 'Supper Administrator',
	            'slug' => 'supper-administrator',
	            'description' => 'Supper Administrator Admin Role',
	            'level' => 5,
        	]);
	    }

    	if (Role::where('name', '=', 'Adminitrator')->first() === null) {
	        $userRole = Role::create([
	            'name' => 'Adminitrator',
	            'slug' => 'adminitrator',
	            'description' => 'Adminitrator Role',
	            'level' => 1,
	        ]);
	    }

    	if (Role::where('name', '=', 'User')->first() === null) {
	        $userRole = Role::create([
	            'name' => 'User',
	            'slug' => 'user',
	            'description' => 'User Role',
	            'level' => 0,
	        ]);
	    }
		if (Role::where('name', '=', 'Unverified')->first() === null) {
	        $userRole = Role::create([
	            'name' => 'Unverified',
	            'slug' => 'unverified',
	            'description' => 'Unverified Role',
	            'level' => 0,
	        ]);
	    }

    }

}