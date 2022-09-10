<?php
namespace App\Repositories\Implementations\Eloquent\HRM;
use App\Repositories\Contracts\HRM\EmployeeBudgetRepository;
use App\Model\HRM\EmployeeBudget;
use App\Traits\Eloquent\MsTraits; 
class EmployeeBudgetImplementation implements EmployeeBudgetRepository
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
	public function __construct(EmployeeBudget $model)
	{
		$this->model = $model;
	}
	
	
}