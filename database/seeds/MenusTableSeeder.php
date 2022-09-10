<?php

use Illuminate\Database\Seeder;
use App\Model\System\Menu;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $system = new Menu();
		$system->id = 1;
		$system->name = 'System';
		$system->router = '';
		$system->root_id = 0;
		$system->sort_id = 1;
		$system->save();
		
		$menu = new Menu();
		$menu->id = 2;
		$menu->name = 'Menu';
		$menu->router = '';
		$menu->root_id = 1;
		$menu->sort_id = 1;
		$menu->save();
		
		$menuManager = new Menu();
		$menuManager->id = 3;
		$menuManager->name = 'Menu Manager';
		$menuManager->router = 'menu/create';
		$menuManager->root_id = 2;
		$menuManager->sort_id = 1;
		$menuManager->save();
		
		$permission = new Menu();
		$permission->id = 4;
		$permission->name = 'Permission';
		$permission->router = 'permission/create';
		$permission->root_id = 2;
		$permission->sort_id = 2;
		$permission->save();
		
		$Accounts = new Menu();
		$Accounts->id = 5;
		$Accounts->name = 'User Accounts';
		$Accounts->router = '';
		$Accounts->root_id = 1;
		$Accounts->sort_id = 1;
		$Accounts->save();
		
		$Role = new Menu();
		$Role->id = 6;
		$Role->name = 'Role';
		$Role->router = '';
		$Role->root_id = 5;
		$Role->sort_id = 1;
		$Role->save();
		
		$User = new Menu();
		$User->id = 7;
		$User->name = 'User';
		$User->router = '';
		$User->root_id = 5;
		$User->sort_id = 2;
		$User->save();
    }
}
