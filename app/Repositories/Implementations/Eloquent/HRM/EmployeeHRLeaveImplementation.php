<?php
namespace App\Repositories\Implementations\Eloquent\HRM;
use App\Repositories\Contracts\HRM\EmployeeHRLeaveRepository;
use App\Model\HRM\EmployeeHRLeave;
use App\Traits\Eloquent\MsTraits; 
class EmployeeHRLeaveImplementation implements EmployeeHRLeaveRepository
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
	public function __construct(EmployeeHRLeave $model)
	{
		$this->model = $model;
	}
	
	
}