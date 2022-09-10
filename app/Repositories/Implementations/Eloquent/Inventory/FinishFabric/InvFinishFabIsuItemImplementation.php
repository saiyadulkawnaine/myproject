<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\FinishFabric;
use App\Repositories\Contracts\Inventory\FinishFabric\InvFinishFabIsuItemRepository;
use App\Model\Inventory\FinishFabric\InvFinishFabIsuItem;
use App\Traits\Eloquent\MsTraits; 
class InvFinishFabIsuItemImplementation implements InvFinishFabIsuItemRepository
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
	public function __construct(InvFinishFabIsuItem $model)
	{
		$this->model = $model;
	}
}