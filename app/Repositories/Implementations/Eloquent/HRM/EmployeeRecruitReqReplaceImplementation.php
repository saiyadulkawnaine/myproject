<?php
namespace App\Repositories\Implementations\Eloquent\HRM;
use App\Repositories\Contracts\HRM\EmployeeRecruitReqReplaceRepository;
use App\Model\HRM\EmployeeRecruitReqReplace;
use App\Traits\Eloquent\MsTraits; 
class EmployeeRecruitReqReplaceImplementation implements EmployeeRecruitReqReplaceRepository
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
	public function __construct(EmployeeRecruitReqReplace $model)
	{
		$this->model = $model;
	}
	
	
}