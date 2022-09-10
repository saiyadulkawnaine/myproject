<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\Yarn;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnTransactionRepository;
use App\Model\Inventory\Yarn\InvYarnTransaction;
use App\Traits\Eloquent\MsTraits; 
class InvYarnTransactionImplementation implements InvYarnTransactionRepository
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
	public function __construct(InvYarnTransaction $model)
	{
		$this->model = $model;
	}
	
	
}