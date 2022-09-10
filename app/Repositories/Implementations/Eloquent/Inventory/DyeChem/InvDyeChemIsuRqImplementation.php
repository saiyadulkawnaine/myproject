<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\DyeChem;
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemIsuRqRepository;
use App\Model\Inventory\DyeChem\InvDyeChemIsuRq;
use App\Traits\Eloquent\MsTraits; 
class InvDyeChemIsuRqImplementation implements InvDyeChemIsuRqRepository
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
	public function __construct(InvDyeChemIsuRq $model)
	{
		$this->model = $model;
	}
	
	
}