<?php
namespace App\Repositories\Implementations\Eloquent\Account;

use App\Repositories\Contracts\Account\AccChartDepartmentRepository;
use App\Model\Account\AccChartDepartment;
use App\Traits\Eloquent\MsTraits; 
class AccChartDepartmentImplementation implements AccChartDepartmentRepository
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
	public function __construct(AccChartDepartment $model)
	{
		$this->model = $model;
	}

	public function getByChartId($acc_chart_ctrl_head_id){
		$department = $this->selectRaw(
		'departments.id,
		departments.name'
		)
		->leftJoin('departments', function($join) {
		$join->on('departments.id', '=', 'acc_chart_departments.department_id');
		})
		->where([['acc_chart_departments.acc_chart_ctrl_head_id','=',$acc_chart_ctrl_head_id]])
		->get();
		return $department;
	}
}