<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\UomconversionRepository;
use App\Model\Util\Uomconversion;
use App\Traits\Eloquent\MsTraits; 
class UomconversionImplementation implements UomconversionRepository
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
	public function __construct(Uomconversion $model)
	{
		$this->model = $model;
	}
}