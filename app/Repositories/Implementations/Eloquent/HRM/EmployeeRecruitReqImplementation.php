<?php
namespace App\Repositories\Implementations\Eloquent\HRM;
use App\Repositories\Contracts\HRM\EmployeeRecruitReqRepository;
use App\Model\HRM\EmployeeRecruitReq;
use App\Traits\Eloquent\MsTraits; 
class EmployeeRecruitReqImplementation implements EmployeeRecruitReqRepository
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
	public function __construct(EmployeeRecruitReq $model)
	{
		$this->model = $model;
	}
	
	
}