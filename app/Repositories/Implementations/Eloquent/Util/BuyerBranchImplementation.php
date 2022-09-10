<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\BuyerBranchRepository;
use App\Model\Util\BuyerBranch;
use App\Traits\Eloquent\MsTraits; 
class BuyerBranchImplementation implements BuyerBranchRepository
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
	public function __construct(BuyerBranch $model)
	{
		$this->model = $model;
	}
}