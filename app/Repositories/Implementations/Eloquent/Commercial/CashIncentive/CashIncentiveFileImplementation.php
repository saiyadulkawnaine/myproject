<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\CashIncentive;

use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveFileRepository;
use App\Model\Commercial\CashIncentive\CashIncentiveFile;
use App\Traits\Eloquent\MsTraits; 
class CashIncentiveFileImplementation implements CashIncentiveFileRepository
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
	public function __construct(CashIncentiveFile $model)
	{
		$this->model = $model;
	}
	
}