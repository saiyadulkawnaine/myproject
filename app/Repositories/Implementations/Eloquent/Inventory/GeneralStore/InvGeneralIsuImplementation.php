<?php

namespace App\Repositories\Implementations\Eloquent\Inventory\GeneralStore;
use App\Repositories\Contracts\Inventory\GeneralStore\InvGeneralIsuRepository;
use App\Model\Inventory\GeneralStore\InvGeneralIsu;
use App\Traits\Eloquent\MsTraits; 
class InvGeneralIsuImplementation implements InvGeneralIsuRepository
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
	public function __construct(InvGeneralIsu $model)
	{
		$this->model = $model;
	}
}