<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\DyeChem;
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemRcvItemRepository;
use App\Model\Inventory\DyeChem\InvDyeChemRcvItem;
use App\Traits\Eloquent\MsTraits; 
class InvDyeChemRcvItemImplementation implements InvDyeChemRcvItemRepository
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
	public function __construct(InvDyeChemRcvItem $model)
	{
		$this->model = $model;
	}
	
	
}