<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\WeightMachineUserRepository;
use App\Model\Util\WeightMachineUser;
use App\Traits\Eloquent\MsTraits; 
class WeightMachineUserImplementation implements WeightMachineUserRepository
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
	public function __construct(WeightMachineUser $model)
	{
		$this->model = $model;
	}
	
	
}