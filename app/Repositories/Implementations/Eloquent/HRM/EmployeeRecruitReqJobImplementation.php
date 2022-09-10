<?php
namespace App\Repositories\Implementations\Eloquent\HRM;
use App\Repositories\Contracts\HRM\EmployeeRecruitReqJobRepository;
use App\Model\HRM\EmployeeRecruitReqJob;
use App\Traits\Eloquent\MsTraits; 
class EmployeeRecruitReqJobImplementation implements EmployeeRecruitReqJobRepository
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
	public function __construct(EmployeeRecruitReqJob $model)
	{
		$this->model = $model;
	}
	
	
}