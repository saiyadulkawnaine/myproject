<?php
namespace App\Repositories\Implementations\Eloquent\HRM;
use App\Repositories\Contracts\HRM\EmployeeHRStatusRepository;
use App\Model\HRM\EmployeeHRStatus;
use App\Traits\Eloquent\MsTraits; 
class EmployeeHRStatusImplementation implements EmployeeHRStatusRepository
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
	public function __construct(EmployeeHRStatus $model)
	{
		$this->model = $model;
	}
	
}