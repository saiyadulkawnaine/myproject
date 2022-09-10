<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\DyeChem;
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemIsuItemRepository;
use App\Model\Inventory\DyeChem\InvDyeChemIsuItem;
use App\Traits\Eloquent\MsTraits; 
class InvDyeChemIsuItemImplementation implements InvDyeChemIsuItemRepository
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
	public function __construct(InvDyeChemIsuItem $model)
	{
		$this->model = $model;
	}
	
	
}