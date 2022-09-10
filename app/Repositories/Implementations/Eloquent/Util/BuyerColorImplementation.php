<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\BuyerColorRepository;
use App\Model\Util\BuyerColor;
use App\Traits\Eloquent\MsTraits; 
class BuyerColorImplementation implements BuyerColorRepository
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
	public function __construct(BuyerColor $model)
	{
		$this->model = $model;
	}
}