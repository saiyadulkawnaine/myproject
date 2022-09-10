<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\Yarn;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnIsuRepository;
use App\Model\Inventory\Yarn\InvYarnIsu;
use App\Traits\Eloquent\MsTraits; 
class InvYarnIsuImplementation implements InvYarnIsuRepository
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
	public function __construct(InvYarnIsu $model)
	{
		$this->model = $model;
	}
	
	
}