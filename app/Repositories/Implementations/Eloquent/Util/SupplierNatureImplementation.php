<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\SupplierNatureRepository;
use App\Model\Util\SupplierNature;
use App\Traits\Eloquent\MsTraits; 
class SupplierNatureImplementation implements SupplierNatureRepository
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
	public function __construct(SupplierNature $model)
	{
		$this->model = $model;
	}
	
	
}