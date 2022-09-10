<?php
namespace App\Repositories\Implementations\Eloquent\HRM;
use App\Repositories\Contracts\HRM\EmployeeHRJobRepository;
use App\Model\HRM\EmployeeHRJob;
use App\Traits\Eloquent\MsTraits; 
class EmployeeHRJobImplementation implements EmployeeHRJobRepository
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
	public function __construct(EmployeeHRJob $model)
	{
		$this->model = $model;
	}
	
	
}