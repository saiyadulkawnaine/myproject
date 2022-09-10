<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\DyeChem;
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemTransactionRepository;
use App\Model\Inventory\DyeChem\InvDyeChemTransaction;
use App\Traits\Eloquent\MsTraits; 
class InvDyeChemTransactionImplementation implements InvDyeChemTransactionRepository
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
	public function __construct(InvDyeChemTransaction $model)
	{
		$this->model = $model;
	}
	
	
}