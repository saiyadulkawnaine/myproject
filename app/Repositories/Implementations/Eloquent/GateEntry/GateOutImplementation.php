<?php
namespace App\Repositories\Implementations\Eloquent\GateEntry;

use App\Repositories\Contracts\GateEntry\GateOutRepository;
use App\Model\GateEntry\GateOut;
use App\Traits\Eloquent\MsTraits; 
class GateOutImplementation implements GateOutRepository
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
	public function __construct(GateOut $model)
	{
		$this->model = $model;
	}
	
	
}