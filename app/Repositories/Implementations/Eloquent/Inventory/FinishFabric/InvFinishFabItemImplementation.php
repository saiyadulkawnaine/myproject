<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\FinishFabric;
use App\Repositories\Contracts\Inventory\FinishFabric\InvFinishFabItemRepository;
use App\Model\Inventory\FinishFabric\InvFinishFabItem;
use App\Traits\Eloquent\MsTraits; 
class InvFinishFabItemImplementation implements InvFinishFabItemRepository
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
	public function __construct(InvFinishFabItem $model)
	{
		$this->model = $model;
	}
}