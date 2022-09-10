<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\CashIncentive;

use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveRefRepository;
use App\Model\Commercial\CashIncentive\CashIncentiveRef;
use App\Traits\Eloquent\MsTraits; 
class CashIncentiveRefImplementation implements CashIncentiveRefRepository
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
	public function __construct(CashIncentiveRef $model)
	{
		$this->model = $model;
	}
	
	
}