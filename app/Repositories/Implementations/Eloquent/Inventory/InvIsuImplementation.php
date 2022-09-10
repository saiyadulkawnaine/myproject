<?php
namespace App\Repositories\Implementations\Eloquent\Inventory;
use App\Repositories\Contracts\Inventory\InvIsuRepository;
use App\Model\Inventory\InvIsu;
use App\Traits\Eloquent\MsTraits; 
class InvIsuImplementation implements InvIsuRepository
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
	public function __construct(InvIsu $model)
	{
		$this->model = $model;
	}
}