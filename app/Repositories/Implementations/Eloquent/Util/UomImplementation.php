<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\UomRepository;
use App\Model\Util\Uom;
use App\Traits\Eloquent\MsTraits; 
class UomImplementation implements UomRepository
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
	public function __construct(Uom $model)
	{
		$this->model = $model;
	}
}