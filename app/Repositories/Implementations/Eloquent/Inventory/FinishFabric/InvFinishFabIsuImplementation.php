<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\FinishFabric;
use App\Repositories\Contracts\Inventory\FinishFabric\InvFinishFabIsuRepository;
use App\Model\Inventory\FinishFabric\InvFinishFabIsu;
use App\Traits\Eloquent\MsTraits; 
class InvFinishFabIsuImplementation implements InvFinishFabIsuRepository
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
	public function __construct(InvFinishFabIsu $model)
	{
		$this->model = $model;
	}
	
	
}