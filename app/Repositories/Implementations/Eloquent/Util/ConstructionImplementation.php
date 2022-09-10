<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\ConstructionRepository;
use App\Model\Util\Construction;
use App\Traits\Eloquent\MsTraits; 
class ConstructionImplementation implements ConstructionRepository
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
	public function __construct(Construction $model)
	{
		$this->model = $model;
	}
}