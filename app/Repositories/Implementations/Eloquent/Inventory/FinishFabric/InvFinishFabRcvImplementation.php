<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\FinishFabric;
use App\Repositories\Contracts\Inventory\FinishFabric\InvFinishFabRcvRepository;
use App\Model\Inventory\FinishFabric\InvFinishFabRcv;
use App\Traits\Eloquent\MsTraits; 
class InvFinishFabRcvImplementation implements InvFinishFabRcvRepository
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
	public function __construct(InvFinishFabRcv $model)
	{
		$this->model = $model;
	}
	
	
}