<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Model\Util\Itemcategory;
use App\Traits\Eloquent\MsTraits; 
class ItemcategoryImplementation implements ItemcategoryRepository
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
	public function __construct(Itemcategory $model)
	{
		$this->model = $model;
	}
	public function getForCombo($identity)
	{
		 //$categories=array();
		if(\Auth::user()->level() == 5){
			$categories=$this->selectRaw(
			'itemcategories.*'
			)
			->whereIn('identity',$identity)
			->get();
		}
		else {
           $categories=$this->selectRaw(
			'itemcategories.*'
			)
			->join('itemcategory_users', function($join) {
				$join->on('itemcategory_users.itemcategory_id', '=', 'itemcategories.id');
			})
			->where([['itemcategory_users.user_id','=',\Auth::user()->id]])
			->whereIn('itemcategories.identity',$identity)
			->whereNull('itemcategory_users.deleted_at')
			->get();
		}
		return $categories;
	}
}