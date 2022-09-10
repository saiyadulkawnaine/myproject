<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\GeneralStore;
use App\Repositories\Contracts\Inventory\GeneralStore\InvPurReqItemRepository;
use App\Model\Inventory\GeneralStore\InvPurReqItem;
use App\Traits\Eloquent\MsTraits; 
class InvPurReqItemImplementation implements InvPurReqItemRepository
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
	public function __construct(InvPurReqItem $model)
	{
		$this->model = $model;
	}
	
	
}