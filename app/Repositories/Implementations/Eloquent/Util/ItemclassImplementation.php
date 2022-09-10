<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Model\Util\Itemclass;
use App\Traits\Eloquent\MsTraits; 
class ItemclassImplementation implements ItemclassRepository
{
	use MsTraits;
	
	/**
	 * @var $model
	 */
	private $model;
 
	/**
	 * MsSysUserImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(Itemclass $model)
	{
		$this->model = $model;
		
	}
	public function getAccessories(){
		$accessories=$this->model->join('itemcategories', function($join){
			$join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
		})
		->where([['itemcategories.identity','=',6]])
		->get([
		'itemclasses.*'
		]);
		return $accessories;
	}
}