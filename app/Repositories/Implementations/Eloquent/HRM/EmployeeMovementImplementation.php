<?php
namespace App\Repositories\Implementations\Eloquent\HRM;
use App\Repositories\Contracts\HRM\EmployeeMovementRepository;
use App\Model\HRM\EmployeeMovement;
use App\Traits\Eloquent\MsTraits; 
class EmployeeMovementImplementation implements EmployeeMovementRepository
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
	public function __construct(EmployeeMovement $model)
	{
		$this->model = $model;
	}
	
	
}