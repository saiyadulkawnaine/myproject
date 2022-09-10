<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\GeneralStore;
use App\Repositories\Contracts\Inventory\GeneralStore\InvGeneralTransactionRepository;
use App\Model\Inventory\GeneralStore\InvGeneralTransaction;
use App\Traits\Eloquent\MsTraits; 
class InvGeneralTransactionImplementation implements InvGeneralTransactionRepository
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
	public function __construct(InvGeneralTransaction $model)
	{
		$this->model = $model;
	}
	
	
}