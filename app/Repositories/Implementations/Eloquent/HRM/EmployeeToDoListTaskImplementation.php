<?php
namespace App\Repositories\Implementations\Eloquent\HRM;
use App\Repositories\Contracts\HRM\EmployeeToDoListTaskRepository;
use App\Model\HRM\EmployeeToDoListTask;
use App\Traits\Eloquent\MsTraits; 
class EmployeeToDoListTaskImplementation implements EmployeeToDoListTaskRepository
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
	public function __construct(EmployeeToDoListTask $model)
	{
		$this->model = $model;
	}
	
	
}