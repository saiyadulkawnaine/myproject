<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\WorkingHourSetupRepository;
use App\Model\Util\WorkingHourSetup;
use App\Traits\Eloquent\MsTraits; 
class WorkingHourSetupImplementation implements WorkingHourSetupRepository
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
	public function __construct(WorkingHourSetup $model)
	{
		$this->model = $model;
	}
}