<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Model\Util\Store;
use App\Traits\Eloquent\MsTraits; 
class StoreImplementation implements StoreRepository
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
	public function __construct(Store $model)
	{
		$this->model = $model;
	}
	
	
}