<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\BuyerBranchShipdayRepository;
use App\Model\Util\BuyerBranchShipday;
use App\Traits\Eloquent\MsTraits; 
class BuyerBranchShipdayImplementation implements BuyerBranchShipdayRepository
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
	public function __construct(BuyerBranchShipday $model)
	{
		$this->model = $model;
	}
}