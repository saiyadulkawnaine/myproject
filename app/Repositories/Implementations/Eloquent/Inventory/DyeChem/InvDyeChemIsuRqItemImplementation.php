<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\DyeChem;
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemIsuRqItemRepository;
use App\Model\Inventory\DyeChem\InvDyeChemIsuRqItem;
use App\Traits\Eloquent\MsTraits; 
class InvDyeChemIsuRqItemImplementation implements InvDyeChemIsuRqItemRepository
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
	public function __construct(InvDyeChemIsuRqItem $model)
	{
		$this->model = $model;
	}
	
	
}