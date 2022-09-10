<?php
namespace App\Repositories\Implementations\Eloquent\HRM;
use App\Repositories\Contracts\HRM\EmployeeJobHistoryRepository;
use App\Model\HRM\EmployeeJobHistory;
use App\Traits\Eloquent\MsTraits; 
class EmployeeJobHistoryImplementation implements EmployeeJobHistoryRepository
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
	public function __construct(EmployeeJobHistory $model)
	{
		$this->model = $model;
	}
	
	
}