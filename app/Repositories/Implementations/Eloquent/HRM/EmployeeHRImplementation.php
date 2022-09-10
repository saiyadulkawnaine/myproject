<?php
namespace App\Repositories\Implementations\Eloquent\HRM;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Model\HRM\EmployeeHR;
use App\Traits\Eloquent\MsTraits; 
class EmployeeHRImplementation implements EmployeeHRRepository
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
	public function __construct(EmployeeHR $model)
	{
		$this->model = $model;
	}
	
	
}