<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\CashIncentive;

use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveYarnBtbLcRepository;
use App\Model\Commercial\CashIncentive\CashIncentiveYarnBtbLc;
use App\Traits\Eloquent\MsTraits; 
class CashIncentiveYarnBtbLcImplementation implements CashIncentiveYarnBtbLcRepository
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
	public function __construct(CashIncentiveYarnBtbLc $model)
	{
		$this->model = $model;
	}
	
	
}