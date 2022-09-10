<?php
namespace App\Repositories\Implementations\Eloquent\Account;

use App\Repositories\Contracts\Account\AccChartDivisionRepository;
use App\Model\Account\AccChartDivision;
use App\Traits\Eloquent\MsTraits; 
class AccChartDivisionImplementation implements AccChartDivisionRepository
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
	public function __construct(AccChartDivision $model)
	{
		$this->model = $model;
	}

	public function getByChartId($acc_chart_ctrl_head_id){
		$locations = $this->selectRaw(
		'divisions.id,
		divisions.name'
		)
		->leftJoin('divisions', function($join) {
		$join->on('divisions.id', '=', 'acc_chart_divisions.division_id');
		})
		->where([['acc_chart_divisions.acc_chart_ctrl_head_id','=',$acc_chart_ctrl_head_id]])
		->get();
		return $locations;
	}
	
	
}