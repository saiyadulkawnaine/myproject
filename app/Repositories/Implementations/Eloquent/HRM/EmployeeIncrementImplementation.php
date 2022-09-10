<?php
namespace App\Repositories\Implementations\Eloquent\HRM;
use App\Repositories\Contracts\HRM\EmployeeIncrementRepository;
use App\Model\HRM\EmployeeIncrement;
use App\Traits\Eloquent\MsTraits; 
class EmployeeIncrementImplementation implements EmployeeIncrementRepository
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
	public function __construct(EmployeeIncrement $model)
	{
		$this->model = $model;
	}
	
	
}