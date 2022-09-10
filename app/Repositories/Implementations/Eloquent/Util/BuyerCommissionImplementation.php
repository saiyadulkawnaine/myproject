<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\BuyerCommissionRepository;
use App\Model\Util\BuyerCommission;
use App\Traits\Eloquent\MsTraits; 
class BuyerCommissionImplementation implements BuyerCommissionRepository
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
	public function __construct(BuyerCommission $model)
	{
		$this->model = $model;
	}
}