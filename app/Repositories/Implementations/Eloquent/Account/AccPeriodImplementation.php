<?php
namespace App\Repositories\Implementations\Eloquent\Account;
use App\Repositories\Contracts\Account\AccPeriodRepository;
use App\Model\Account\AccPeriod;
use App\Traits\Eloquent\MsTraits; 
class AccPeriodImplementation implements AccPeriodRepository
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
	public function __construct(AccPeriod $model)
	{
		$this->model = $model;
	}
	
	
}