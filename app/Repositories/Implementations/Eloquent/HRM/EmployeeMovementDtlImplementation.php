<?php
namespace App\Repositories\Implementations\Eloquent\HRM;
use App\Repositories\Contracts\HRM\EmployeeMovementDtlRepository;
use App\Model\HRM\EmployeeMovementDtl;
use App\Traits\Eloquent\MsTraits; 
class EmployeeMovementDtlImplementation implements EmployeeMovementDtlRepository
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
	public function __construct(EmployeeMovementDtl $model)
	{
		$this->model = $model;
	}
	
	
}