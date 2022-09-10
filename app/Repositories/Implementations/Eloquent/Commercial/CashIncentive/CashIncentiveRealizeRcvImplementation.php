<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\CashIncentive;

use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveRealizeRcvRepository;
use App\Model\Commercial\CashIncentive\CashIncentiveRealizeRcv;
use App\Traits\Eloquent\MsTraits; 
class CashIncentiveRealizeRcvImplementation implements CashIncentiveRealizeRcvRepository
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
	public function __construct(CashIncentiveRealizeRcv $model)
	{
		$this->model = $model;
	}
	
	
}