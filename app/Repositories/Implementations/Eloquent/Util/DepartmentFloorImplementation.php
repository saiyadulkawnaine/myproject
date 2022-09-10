<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\DepartmentFloorRepository;
use App\Model\Util\DepartmentFloor;
use App\Traits\Eloquent\MsTraits; 
class DepartmentFloorImplementation implements DepartmentFloorRepository
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
	public function __construct(DepartmentFloor $model)
	{
		$this->model = $model;
	}
	
	
}