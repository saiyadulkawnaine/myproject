<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\GreyFabric;
use App\Repositories\Contracts\Inventory\GreyFabric\InvGreyFabIsuRepository;
use App\Model\Inventory\GreyFabric\InvGreyFabIsu;
use App\Traits\Eloquent\MsTraits; 
class InvGreyFabIsuImplementation implements InvGreyFabIsuRepository
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
	public function __construct(InvGreyFabIsu $model)
	{
		$this->model = $model;
	}
	
	
}