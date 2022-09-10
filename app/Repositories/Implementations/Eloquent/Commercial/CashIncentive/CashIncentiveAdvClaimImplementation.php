<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\CashIncentive;

use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveAdvClaimRepository;
use App\Model\Commercial\CashIncentive\CashIncentiveAdvClaim;
use App\Traits\Eloquent\MsTraits; 
class CashIncentiveAdvClaimImplementation implements CashIncentiveAdvClaimRepository
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
	public function __construct(CashIncentiveAdvClaim $model)
	{
		$this->model = $model;
	}
	
}