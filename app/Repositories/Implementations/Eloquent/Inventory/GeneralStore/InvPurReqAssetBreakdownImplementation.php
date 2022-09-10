<?php

namespace App\Repositories\Implementations\Eloquent\Inventory\GeneralStore;
use App\Repositories\Contracts\Inventory\GeneralStore\InvPurReqAssetBreakdownRepository;
use App\Model\Inventory\GeneralStore\InvPurReqAssetBreakdown;
use App\Traits\Eloquent\MsTraits; 
class InvPurReqAssetBreakdownImplementation implements InvPurReqAssetBreakdownRepository
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
	public function __construct(InvPurReqAssetBreakdown $model)
	{
		$this->model = $model;
	}
	
	
}