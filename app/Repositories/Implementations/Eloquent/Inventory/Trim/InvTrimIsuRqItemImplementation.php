<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\Trim;
use App\Repositories\Contracts\Inventory\Trim\InvTrimIsuRqItemRepository;
use App\Model\Inventory\Trim\InvTrimIsuRqItem;
use App\Traits\Eloquent\MsTraits; 
class InvTrimIsuRqItemImplementation implements InvTrimIsuRqItemRepository
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
	public function __construct(InvTrimIsuRqItem $model)
	{
		$this->model = $model;
	}
	
	
}