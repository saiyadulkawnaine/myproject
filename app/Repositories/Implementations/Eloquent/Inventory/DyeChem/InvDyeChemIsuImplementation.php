<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\DyeChem;
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemIsuRepository;
use App\Model\Inventory\DyeChem\InvDyeChemIsu;
use App\Traits\Eloquent\MsTraits; 
class InvDyeChemIsuImplementation implements InvDyeChemIsuRepository
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
	public function __construct(InvDyeChemIsu $model)
	{
		$this->model = $model;
	}
	
	
}