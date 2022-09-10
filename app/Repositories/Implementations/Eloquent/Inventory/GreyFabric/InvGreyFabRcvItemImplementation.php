<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\GreyFabric;
use App\Repositories\Contracts\Inventory\GreyFabric\InvGreyFabRcvItemRepository;
use App\Model\Inventory\GreyFabric\InvGreyFabRcvItem;
use App\Traits\Eloquent\MsTraits; 
class InvGreyFabRcvItemImplementation implements InvGreyFabRcvItemRepository
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
	public function __construct(InvGreyFabRcvItem $model)
	{
		$this->model = $model;
	}
}