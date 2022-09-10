<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\GreyFabric;
use App\Repositories\Contracts\Inventory\GreyFabric\InvGreyFabIsuItemRepository;
use App\Model\Inventory\GreyFabric\InvGreyFabIsuItem;
use App\Traits\Eloquent\MsTraits; 
class InvGreyFabIsuItemImplementation implements InvGreyFabIsuItemRepository
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
	public function __construct(InvGreyFabIsuItem $model)
	{
		$this->model = $model;
	}
}