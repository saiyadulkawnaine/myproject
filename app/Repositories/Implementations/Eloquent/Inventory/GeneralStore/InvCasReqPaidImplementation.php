<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\GeneralStore;
use App\Repositories\Contracts\Inventory\GeneralStore\InvCasReqPaidRepository;
use App\Model\Inventory\GeneralStore\InvCasReqPaid;
use App\Traits\Eloquent\MsTraits; 
class InvCasReqPaidImplementation implements InvCasReqPaidRepository
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
	public function __construct(InvCasReqPaid $model)
	{
		$this->model = $model;
	}
	
	
}