<?php
namespace App\Repositories\Implementations\Eloquent\GateEntry;

use App\Repositories\Contracts\GateEntry\GateEntryItemRepository;
use App\Model\GateEntry\GateEntryItem;
use App\Traits\Eloquent\MsTraits; 
class GateEntryItemImplementation implements GateEntryItemRepository
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
	public function __construct(GateEntryItem $model)
	{
		$this->model = $model;
	}
	
	
}