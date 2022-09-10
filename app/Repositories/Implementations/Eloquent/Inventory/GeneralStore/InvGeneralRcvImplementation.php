<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\GeneralStore;
use App\Repositories\Contracts\Inventory\GeneralStore\InvGeneralRcvRepository;
use App\Model\Inventory\GeneralStore\InvGeneralRcv;
use App\Traits\Eloquent\MsTraits; 
class InvGeneralRcvImplementation implements InvGeneralRcvRepository
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
	public function __construct(InvGeneralRcv $model)
	{
		$this->model = $model;
	}
	
	
}