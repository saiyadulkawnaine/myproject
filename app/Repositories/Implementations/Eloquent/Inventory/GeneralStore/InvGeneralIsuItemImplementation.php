<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\GeneralStore;
use App\Repositories\Contracts\Inventory\GeneralStore\InvGeneralIsuItemRepository;
use App\Model\Inventory\GeneralStore\InvGeneralIsuItem;
use App\Traits\Eloquent\MsTraits; 
class InvGeneralIsuItemImplementation implements InvGeneralIsuItemRepository
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
	public function __construct(InvGeneralIsuItem $model)
	{
		$this->model = $model;
	}
}