<?php

namespace App\Repositories\Implementations\Eloquent\Inventory\GeneralStore;
use App\Repositories\Contracts\Inventory\GeneralStore\InvGeneralIsuRqRepository;
use App\Model\Inventory\GeneralStore\InvGeneralIsuRq;
use App\Traits\Eloquent\MsTraits; 
class InvGeneralIsuRqImplementation implements InvGeneralIsuRqRepository
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
	public function __construct(InvGeneralIsuRq $model)
	{
		$this->model = $model;
	}
}