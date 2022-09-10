<?php

namespace App\Repositories\Implementations\Eloquent\Inventory\GeneralStore;
use App\Repositories\Contracts\Inventory\GeneralStore\InvPurReqRepository;
use App\Model\Inventory\GeneralStore\InvPurReq;
use App\Traits\Eloquent\MsTraits; 
class InvPurReqImplementation implements InvPurReqRepository
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
	public function __construct(InvPurReq $model)
	{
		$this->model = $model;
	}
	
	
}