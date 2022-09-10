<?php
namespace App\Repositories\Implementations\Eloquent\Account;

use App\Repositories\Contracts\Account\AccChartSubGroupRepository;
use App\Model\Account\AccChartSubGroup;
use App\Traits\Eloquent\MsTraits; 
class AccChartSubGroupImplementation implements AccChartSubGroupRepository
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
	public function __construct(AccChartSubGroup $model)
	{
		$this->model = $model;
	}
	
	
}