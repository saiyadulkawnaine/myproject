<?php
namespace App\Repositories\Implementations\Eloquent\Account;

use App\Repositories\Contracts\Account\AccChartSectionRepository;
use App\Model\Account\AccChartSection;
use App\Traits\Eloquent\MsTraits; 
class AccChartSectionImplementation implements AccChartSectionRepository
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
	public function __construct(AccChartSection $model)
	{
		$this->model = $model;
	}

	public function getByChartId($acc_chart_ctrl_head_id){
		$sections = $this->selectRaw(
		'sections.id,
		sections.name'
		)
		->leftJoin('sections', function($join) {
		$join->on('sections.id', '=', 'acc_chart_sections.section_id');
		})
		->where([['acc_chart_sections.acc_chart_ctrl_head_id','=',$acc_chart_ctrl_head_id]])
		->get();
		return $sections;
	}
	
	
}