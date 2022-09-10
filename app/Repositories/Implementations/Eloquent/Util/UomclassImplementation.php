<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\UomclassRepository;
use App\Model\Util\Uomclass;
use App\Traits\Eloquent\MsTraits; 
class UomclassImplementation implements UomclassRepository
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
	public function __construct(Uomclass $model)
	{
		$this->model = $model;
	}
}