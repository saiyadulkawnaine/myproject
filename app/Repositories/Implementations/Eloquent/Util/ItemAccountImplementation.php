<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Model\Util\ItemAccount;
use App\Traits\Eloquent\MsTraits;
class ItemAccountImplementation implements ItemAccountRepository
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
	public function __construct(ItemAccount $model)
	{
		$this->model = $model;
	}
	public function getAccessories(){
		$accessories=$this->model->join('itemcategories', function($join){
			$join->on('itemcategories.id', '=', 'item_accounts.itemcategory_id');
		})
		->where([['itemcategories.identity','=',6]])
		->get([
		'item_accounts.*'
		]);
		return $accessories;
	}
}
