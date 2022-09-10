<?php
/**
 * @author Md. Monzurul Haque <monzu860@yahoo.com>
 * @param string $pageName defining page name
 * @param array $data defining dynamic values passed to view
 * @return custom view
 */
namespace App\Library;
use View;

class Template
{
    
    public static function loadView($pageName, $data = array())
    {
        //$data['pageTitle'] = View::make("layouts.breadcrumb", $data)->render();
		$viewdefult=config('viewdefult.defult');
        if (\Request::ajax()) {
			return view()->first([$viewdefult.".".$pageName, "Defult.".$pageName], $data);
        } else {
			$menu = \App::make(\App\Repositories\Contracts\System\MenuRepository::class);
			$data["menus"]=$menu->getTree('My Menu','html');
			$data["viewdefult"]=$viewdefult;
			return view()->first([$viewdefult.".System.Layout.home", "Defult.System.Layout.home"], $data);
        }
    }
}