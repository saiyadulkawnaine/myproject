<?php
namespace App\Repositories\Implementations\Eloquent\GateEntry;

use App\Repositories\Contracts\GateEntry\GateOutItemRepository;
use App\Model\GateEntry\GateOutItem;
use App\Traits\Eloquent\MsTraits; 
class GateOutItemImplementation implements GateOutItemRepository
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
	public function __construct(GateOutItem $model)
	{
		$this->model = $model;
	}
	
	
}