<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\BuyerUserRepository;
use App\Model\Util\BuyerUser;
use App\Traits\Eloquent\MsTraits; 
class BuyerUserImplementation implements BuyerUserRepository
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
	public function __construct(BuyerUser $model)
	{
		$this->model = $model;
	}
	
	
}