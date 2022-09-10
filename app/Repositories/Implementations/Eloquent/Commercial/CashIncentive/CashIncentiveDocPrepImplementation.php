<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\CashIncentive;

use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveDocPrepRepository;
use App\Model\Commercial\CashIncentive\CashIncentiveDocPrep;
use App\Traits\Eloquent\MsTraits; 
class CashIncentiveDocPrepImplementation implements CashIncentiveDocPrepRepository
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
	public function __construct(CashIncentiveDocPrep $model)
	{
		$this->model = $model;
	}
	
	
}