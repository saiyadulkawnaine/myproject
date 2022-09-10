<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\FinishFabric;
use App\Repositories\Contracts\Inventory\FinishFabric\InvFinishFabRcvFabricRepository;
use App\Model\Inventory\FinishFabric\InvFinishFabRcvFabric;
use App\Traits\Eloquent\MsTraits; 
class InvFinishFabRcvFabricImplementation implements InvFinishFabRcvFabricRepository
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
	public function __construct(InvFinishFabRcvFabric $model)
	{
		$this->model = $model;
	}
	
	
}