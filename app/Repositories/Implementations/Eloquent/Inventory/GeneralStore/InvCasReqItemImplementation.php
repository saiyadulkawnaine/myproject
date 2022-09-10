<?php

namespace App\Repositories\Implementations\Eloquent\Inventory\GeneralStore;
use App\Repositories\Contracts\Inventory\GeneralStore\InvCasReqItemRepository;
use App\Model\Inventory\GeneralStore\InvCasReqItem;
use App\Traits\Eloquent\MsTraits; 
class InvCasReqItemImplementation implements InvCasReqItemRepository
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
	public function __construct(InvCasReqItem $model)
	{
		$this->model = $model;
	}
	
	
}