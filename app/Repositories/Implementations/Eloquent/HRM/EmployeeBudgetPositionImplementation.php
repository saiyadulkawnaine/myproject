<?php
namespace App\Repositories\Implementations\Eloquent\HRM;
use App\Repositories\Contracts\HRM\EmployeeBudgetPositionRepository;
use App\Model\HRM\EmployeeBudgetPosition;
use App\Traits\Eloquent\MsTraits; 
class EmployeeBudgetPositionImplementation implements EmployeeBudgetPositionRepository
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
	public function __construct(EmployeeBudgetPosition $model)
	{
		$this->model = $model;
	}
	
	
}