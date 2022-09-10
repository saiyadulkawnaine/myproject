<?php
namespace App\Repositories\Implementations\Eloquent\Account;
use App\Repositories\Contracts\Account\AccCostDistributionDtlRepository;
use App\Model\Account\AccCostDistributionDtl;
use App\Traits\Eloquent\MsTraits; 
class AccCostDistributionDtlImplementation implements AccCostDistributionDtlRepository
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
	public function __construct(AccCostDistributionDtl $model)
	{
		$this->model = $model;
	}
	
}
