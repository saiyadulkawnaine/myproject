<?php
namespace App\Repositories\Implementations\Eloquent\Account;

use App\Repositories\Contracts\Account\AccChartLocationRepository;
use App\Model\Account\AccChartLocation;
use App\Traits\Eloquent\MsTraits; 
class AccChartLocationImplementation implements AccChartLocationRepository
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
	public function __construct(AccChartLocation $model)
	{
		$this->model = $model;
	}

	public function getByChartId($acc_chart_ctrl_head_id){
		$locations = $this->selectRaw(
		'locations.id,
		locations.name'
		)
		->leftJoin('locations', function($join) {
		$join->on('locations.id', '=', 'acc_chart_locations.location_id');
		})
		->where([['acc_chart_locations.acc_chart_ctrl_head_id','=',$acc_chart_ctrl_head_id]])
		->get();
		return $locations;
	}
}