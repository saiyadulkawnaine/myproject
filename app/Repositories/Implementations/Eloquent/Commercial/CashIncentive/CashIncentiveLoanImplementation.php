<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\CashIncentive;

use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveLoanRepository;
use App\Model\Commercial\CashIncentive\CashIncentiveLoan;
use App\Traits\Eloquent\MsTraits; 
class CashIncentiveLoanImplementation implements CashIncentiveLoanRepository
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
	public function __construct(CashIncentiveLoan $model)
	{
		$this->model = $model;
	}
	
	
}