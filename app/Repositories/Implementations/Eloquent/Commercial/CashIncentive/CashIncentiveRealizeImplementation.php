<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\CashIncentive;

use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveRealizeRepository;
use App\Model\Commercial\CashIncentive\CashIncentiveRealize;
use App\Traits\Eloquent\MsTraits; 
class CashIncentiveRealizeImplementation implements CashIncentiveRealizeRepository
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
	public function __construct(CashIncentiveRealize $model)
	{
		$this->model = $model;
	}
	
	
}