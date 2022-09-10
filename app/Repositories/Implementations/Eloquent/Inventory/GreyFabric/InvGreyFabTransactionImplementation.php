<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\GreyFabric;
use App\Repositories\Contracts\Inventory\GreyFabric\InvGreyFabTransactionRepository;
use App\Model\Inventory\GreyFabric\InvGreyFabTransaction;
use App\Traits\Eloquent\MsTraits; 
class InvGreyFabTransactionImplementation implements InvGreyFabTransactionRepository
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
	public function __construct(InvGreyFabTransaction $model)
	{
		$this->model = $model;
	}
	
	
}