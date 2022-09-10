<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\CashIncentive;

use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveAdvRepository;
use App\Model\Commercial\CashIncentive\CashIncentiveAdv;
use App\Traits\Eloquent\MsTraits; 
class CashIncentiveAdvImplementation implements CashIncentiveAdvRepository
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
	public function __construct(CashIncentiveAdv $model)
	{
		$this->model = $model;
	}
	
	
}