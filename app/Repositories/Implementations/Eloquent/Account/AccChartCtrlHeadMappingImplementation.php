<?php
namespace App\Repositories\Implementations\Eloquent\Account;

use App\Repositories\Contracts\Account\AccChartCtrlHeadMappingRepository;
use App\Model\Account\AccChartCtrlHeadMapping;
use App\Traits\Eloquent\MsTraits; 
class AccChartCtrlHeadMappingImplementation implements AccChartCtrlHeadMappingRepository
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
	public function __construct(AccChartCtrlHeadMapping $model)
	{
		$this->model = $model;
	}
	
	
}