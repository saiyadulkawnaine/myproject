<?php
namespace App\Repositories\Implementations\Eloquent\Account;

use App\Repositories\Contracts\Account\AccChartCtrlHeadRepository;
use App\Model\Account\AccChartCtrlHead;
use App\Traits\Eloquent\MsTraits; 
class AccChartCtrlHeadImplementation implements AccChartCtrlHeadRepository
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
	public function __construct(AccChartCtrlHead $model)
	{
		$this->model = $model;
	}
	
	
}