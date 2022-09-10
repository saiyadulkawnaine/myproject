<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\GreyFabric;
use App\Repositories\Contracts\Inventory\GreyFabric\InvGreyFabItemRepository;
use App\Model\Inventory\GreyFabric\InvGreyFabItem;
use App\Traits\Eloquent\MsTraits; 
class InvGreyFabItemImplementation implements InvGreyFabItemRepository
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
	public function __construct(InvGreyFabItem $model)
	{
		$this->model = $model;
	}
}