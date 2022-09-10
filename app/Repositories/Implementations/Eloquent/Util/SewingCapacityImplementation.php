<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\SewingCapacityRepository;
use App\Model\Util\SewingCapacity;
use App\Traits\Eloquent\MsTraits; 
class SewingCapacityImplementation implements SewingCapacityRepository
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
	public function __construct(SewingCapacity $model)
	{
		$this->model = $model;
	}
}