<?php
namespace App\Repositories\Implementations\Eloquent\Inventory;
use App\Repositories\Contracts\Inventory\InvRcvRepository;
use App\Model\Inventory\InvRcv;
use App\Traits\Eloquent\MsTraits; 
class InvRcvImplementation implements InvRcvRepository
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
	public function __construct(InvRcv $model)
	{
		$this->model = $model;
	}
}