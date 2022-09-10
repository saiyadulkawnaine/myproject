<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\Trim;
use App\Repositories\Contracts\Inventory\Trim\InvTrimTransactionRepository;
use App\Model\Inventory\Trim\InvTrimTransaction;
use App\Traits\Eloquent\MsTraits; 
class InvTrimTransactionImplementation implements InvTrimTransactionRepository
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
	public function __construct(InvTrimTransaction $model)
	{
		$this->model = $model;
	}
	
	
}