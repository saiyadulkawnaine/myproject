<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\GreyFabric;
use App\Repositories\Contracts\Inventory\GreyFabric\InvGreyFabRcvRepository;
use App\Model\Inventory\GreyFabric\InvGreyFabRcv;
use App\Traits\Eloquent\MsTraits; 
class InvGreyFabRcvImplementation implements InvGreyFabRcvRepository
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
	public function __construct(InvGreyFabRcv $model)
	{
		$this->model = $model;
	}
	
	
}