<?php
namespace App\Repositories\Implementations\Eloquent\HRM;
use App\Repositories\Contracts\HRM\EmployeeToDoListRepository;
use App\Model\HRM\EmployeeToDoList;
use App\Traits\Eloquent\MsTraits; 
class EmployeeToDoListImplementation implements EmployeeToDoListRepository
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
	public function __construct(EmployeeToDoList $model)
	{
		$this->model = $model;
	}
	
	
}