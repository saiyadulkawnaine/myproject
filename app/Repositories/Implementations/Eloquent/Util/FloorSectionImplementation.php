<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\FloorSectionRepository;
use App\Model\Util\FloorSection;
use App\Traits\Eloquent\MsTraits; 
class FloorSectionImplementation implements FloorSectionRepository
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
	public function __construct(FloorSection $model)
	{
		$this->model = $model;
	}
	
	
}