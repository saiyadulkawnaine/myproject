<?php
namespace App\Repositories\Implementations\Eloquent\HRM;
use App\Repositories\Contracts\HRM\EmployeeToDoListTaskBarRepository;
use App\Model\HRM\EmployeeToDoListTaskBar;
use App\Traits\Eloquent\MsTraits; 
class EmployeeToDoListTaskBarImplementation implements EmployeeToDoListTaskBarRepository
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
	public function __construct(EmployeeToDoListTaskBar $model)
	{
		$this->model = $model;
	}
	
	
}