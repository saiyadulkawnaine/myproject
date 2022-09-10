<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\CashIncentive;

use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveFileQueryRepository;
use App\Model\Commercial\CashIncentive\CashIncentiveFileQuery;
use App\Traits\Eloquent\MsTraits; 
class CashIncentiveFileQueryImplementation implements CashIncentiveFileQueryRepository
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
	public function __construct(CashIncentiveFileQuery $model)
	{
		$this->model = $model;
	}
	
}