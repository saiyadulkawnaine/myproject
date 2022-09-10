<?php
namespace App\Repositories\Implementations\Eloquent\HRM;
use App\Repositories\Contracts\HRM\EmployeeAttendenceRepository;
use App\Model\HRM\EmployeeAttendence;
use App\Traits\Eloquent\MsTraits; 
class EmployeeAttendenceImplementation implements EmployeeAttendenceRepository
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
	public function __construct(EmployeeAttendence $model)
	{
		$this->model = $model;
	}
	
	
}