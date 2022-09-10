<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\GeneralStore;
use App\Repositories\Contracts\Inventory\GeneralStore\InvGeneralRcvItemRepository;
use App\Model\Inventory\GeneralStore\InvGeneralRcvItem;
use App\Traits\Eloquent\MsTraits; 
class InvGeneralRcvItemImplementation implements InvGeneralRcvItemRepository
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
	public function __construct(InvGeneralRcvItem $model)
	{
		$this->model = $model;
	}
	
	
}