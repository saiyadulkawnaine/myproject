<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\Yarn;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnRcvRepository;
use App\Model\Inventory\Yarn\InvYarnRcv;
use App\Traits\Eloquent\MsTraits; 
class InvYarnRcvImplementation implements InvYarnRcvRepository
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
	public function __construct(InvYarnRcv $model)
	{
		$this->model = $model;
	}
	
	
}