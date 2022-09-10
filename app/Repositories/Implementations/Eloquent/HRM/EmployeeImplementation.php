<?php
namespace App\Repositories\Implementations\Eloquent\HRM;
use App\Repositories\Contracts\HRM\EmployeeRepository;
use App\Model\HRM\Employee;
use App\Traits\Eloquent\MsTraits; 
class EmployeeImplementation implements EmployeeRepository
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
	public function __construct(Employee $model)
	{
		$this->model = $model;
	}
	
	
}