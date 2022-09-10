<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\FinishFabric;
use App\Repositories\Contracts\Inventory\FinishFabric\InvFinishFabRcvItemRepository;
use App\Model\Inventory\FinishFabric\InvFinishFabRcvItem;
use App\Traits\Eloquent\MsTraits; 
class InvFinishFabRcvItemImplementation implements InvFinishFabRcvItemRepository
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
	public function __construct(InvFinishFabRcvItem $model)
	{
		$this->model = $model;
	}
}