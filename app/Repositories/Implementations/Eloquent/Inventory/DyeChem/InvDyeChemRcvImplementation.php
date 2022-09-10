<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\DyeChem;
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemRcvRepository;
use App\Model\Inventory\DyeChem\InvDyeChemRcv;
use App\Traits\Eloquent\MsTraits; 
class InvDyeChemRcvImplementation implements InvDyeChemRcvRepository
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
	public function __construct(InvDyeChemRcv $model)
	{
		$this->model = $model;
	}
	
	
}