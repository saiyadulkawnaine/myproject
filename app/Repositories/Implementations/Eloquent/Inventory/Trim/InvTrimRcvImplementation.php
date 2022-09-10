<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\Trim;
use App\Repositories\Contracts\Inventory\Trim\InvTrimRcvRepository;
use App\Model\Inventory\Trim\InvTrimRcv;
use App\Traits\Eloquent\MsTraits; 
class InvTrimRcvImplementation implements InvTrimRcvRepository
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
	public function __construct(InvTrimRcv $model)
	{
		$this->model = $model;
	}
	
	
}