<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\WagevariableRepository;
use App\Model\Util\Wagevariable;
use App\Traits\Eloquent\MsTraits; 
class WagevariableImplementation implements WagevariableRepository
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
	public function __construct(Wagevariable $model)
	{
		$this->model = $model;
	}
}