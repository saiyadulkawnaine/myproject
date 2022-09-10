<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\Trim;
use App\Repositories\Contracts\Inventory\Trim\InvTrimItemRepository;
use App\Model\Inventory\Trim\InvTrimItem;
use App\Traits\Eloquent\MsTraits; 
class InvTrimItemImplementation implements InvTrimItemRepository
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
	public function __construct(InvTrimItem $model)
	{
		$this->model = $model;
	}
}