<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\FinishFabric;
use App\Repositories\Contracts\Inventory\FinishFabric\InvFinishFabTransactionRepository;
use App\Model\Inventory\FinishFabric\InvFinishFabTransaction;
use App\Traits\Eloquent\MsTraits; 
class InvFinishFabTransactionImplementation implements InvFinishFabTransactionRepository
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
	public function __construct(InvFinishFabTransaction $model)
	{
		$this->model = $model;
	}
	
	
}