<?php
namespace App\Repositories\Implementations\Eloquent\Account;
use App\Repositories\Contracts\Account\AccChartMasterRepository;
use App\Model\Account\AccChartMaster;
use App\Traits\Eloquent\MsTraits; 
class AccChartMasterImplementation implements AccChartMasterRepository
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
	public function __construct(AccChartMaster $model)
	{
		$this->model = $model;
	}
	
	
}