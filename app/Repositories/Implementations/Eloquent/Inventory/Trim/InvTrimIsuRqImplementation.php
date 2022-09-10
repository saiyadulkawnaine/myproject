<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\Trim;
use App\Repositories\Contracts\Inventory\Trim\InvTrimIsuRqRepository;
use App\Model\Inventory\Trim\InvTrimIsuRq;
use App\Traits\Eloquent\MsTraits; 
class InvTrimIsuRqImplementation implements InvTrimIsuRqRepository
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
	public function __construct(InvTrimIsuRq $model)
	{
		$this->model = $model;
	}
	
	
}