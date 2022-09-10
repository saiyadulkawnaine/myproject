<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\CashIncentive;

use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveClaimRepository;
use App\Model\Commercial\CashIncentive\CashIncentiveClaim;
use App\Traits\Eloquent\MsTraits; 
class CashIncentiveClaimImplementation implements CashIncentiveClaimRepository
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
	public function __construct(CashIncentiveClaim $model)
	{
		$this->model = $model;
	}
	
	
}