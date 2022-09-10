<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\BuyerSizeRepository;
use App\Model\Util\BuyerSize;
use App\Traits\Eloquent\MsTraits; 
class BuyerSizeImplementation implements BuyerSizeRepository
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
	public function __construct(BuyerSize $model)
	{
		$this->model = $model;
	}
}