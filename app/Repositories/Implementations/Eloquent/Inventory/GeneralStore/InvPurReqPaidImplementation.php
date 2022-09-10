<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\GeneralStore;
use App\Repositories\Contracts\Inventory\GeneralStore\InvPurReqPaidRepository;
use App\Model\Inventory\GeneralStore\InvPurReqPaid;
use App\Traits\Eloquent\MsTraits; 
class InvPurReqPaidImplementation implements InvPurReqPaidRepository
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
	public function __construct(InvPurReqPaid $model)
	{
		$this->model = $model;
	}
	
	
}