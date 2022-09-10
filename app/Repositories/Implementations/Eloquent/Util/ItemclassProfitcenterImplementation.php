<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\ItemclassProfitcenterRepository;
use App\Model\Util\ItemclassProfitcenter;
use App\Traits\Eloquent\MsTraits; 
class ItemclassProfitcenterImplementation implements ItemclassProfitcenterRepository
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
	public function __construct(ItemclassProfitcenter $model)
	{
		$this->model = $model;
	}
}