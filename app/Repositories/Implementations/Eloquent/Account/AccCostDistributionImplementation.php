<?php
namespace App\Repositories\Implementations\Eloquent\Account;
use App\Repositories\Contracts\Account\AccCostDistributionRepository;
use App\Model\Account\AccCostDistribution;
use App\Traits\Eloquent\MsTraits; 
class AccCostDistributionImplementation implements AccCostDistributionRepository
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
	public function __construct(AccCostDistribution $model)
	{
		$this->model = $model;
	}
	
}
