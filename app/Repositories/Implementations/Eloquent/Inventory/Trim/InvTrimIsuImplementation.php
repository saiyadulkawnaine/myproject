<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\Trim;
use App\Repositories\Contracts\Inventory\Trim\InvTrimIsuRepository;
use App\Model\Inventory\Trim\InvTrimIsu;
use App\Traits\Eloquent\MsTraits; 
class InvTrimIsuImplementation implements InvTrimIsuRepository
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
	public function __construct(InvTrimIsu $model)
	{
		$this->model = $model;
	}
	
	
}