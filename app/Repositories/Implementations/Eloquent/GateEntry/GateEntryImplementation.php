<?php
namespace App\Repositories\Implementations\Eloquent\GateEntry;

use App\Repositories\Contracts\GateEntry\GateEntryRepository;
use App\Model\GateEntry\GateEntry;
use App\Traits\Eloquent\MsTraits; 
class GateEntryImplementation implements GateEntryRepository
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
	public function __construct(GateEntry $model)
	{
		$this->model = $model;
	}
	
	
}