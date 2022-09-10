<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\SewingCapacityDateRepository;
use App\Model\Util\SewingCapacityDate;
use App\Traits\Eloquent\MsTraits; 
class SewingCapacityDateImplementation implements SewingCapacityDateRepository
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
	public function __construct(SewingCapacityDate $model)
	{
		$this->model = $model;
	}
}