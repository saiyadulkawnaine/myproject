<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\GeneralStore;
use App\Repositories\Contracts\Inventory\GeneralStore\InvGeneralIsuRqItemRepository;
use App\Model\Inventory\GeneralStore\InvGeneralIsuRqItem;
use App\Traits\Eloquent\MsTraits; 
class InvGeneralIsuRqItemImplementation implements InvGeneralIsuRqItemRepository
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
	public function __construct(InvGeneralIsuRqItem $model)
	{
		$this->model = $model;
	}
}