<?php
 
namespace App\Repositories\Implementations\Eloquent\System;
use App\Repositories\Contracts\System\MenuRepository;
use App\Model\System\Menu;
use App\Traits\Eloquent\MsTraits; 
use App\Traits\Eloquent\MsUpdater;
class MenuImplementation implements MenuRepository
{
	 use MsTraits;
	 
	/**
	 * @var $model
	 */
	private $model;
 
	/**
	 * EloquentTask constructor.
	 *
	 * @param App\Task $model
	 */
	public function __construct(Menu $model)
	{
		$this->model = $model;
	}
	
	public  function getTree($root_title,$mode='html',$check=false){
		switch ($mode){
			case 'html':
			$temp ="<ul class='easyui-tree' checkbox='".$check."'  id='left_menu'><li>".$this->tagHtml($root_title,$this->getMenuHtml(0),0)."</li></ul>";
			break;
			case 'json':
			$temp = "[".$this->tagJson($root_title,$this->getMenuJson(0),0)."]";
			break; 
		}
		return $temp;
	}
	
	private function tagHtml($tag_name, $value,$id=0,$tag_link='',$js_link='') {
		$url=url('/').'/'.$tag_link;
		$tmp = '<span><a href="javascript:void(0)" onClick="msApp.PageLoader.load('."'".$url."','".$tag_link."','".$js_link."','menu_content'".')">'.$tag_name.'</a></span>'; 
		if ($value){
			$tmp .= "<ul>"."$value"."</ul>"; 
		} 
		return $tmp;
	}
	
	private function getMenuHtml($parent_id) {
	    $menus = $this->where([['root_id','=',$parent_id]])->orderBy('sort_id', 'asc')->get();
		$temp = "";
		foreach ($menus as $menu) {
			if($menu->router){
				$dataOption='';
			}else{
				$dataOption='closed';
			}
			$temp .="<li data-options=\"state:'".$dataOption."',iconCls:'icon-bird'\">".$this->tagHtml($menu->name,$this->getMenuHtml($menu->id),$menu->id,$menu->router,$menu->js_link)."</li>";
		}
		return $temp;
	}
}