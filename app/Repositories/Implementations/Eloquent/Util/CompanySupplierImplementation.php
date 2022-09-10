<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\CompanySupplierRepository;
use App\Model\Util\CompanySupplier;
use App\Traits\Eloquent\MsTraits; 
class CompanySupplierImplementation implements CompanySupplierRepository
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
	public function __construct(CompanySupplier $model)
	{
		$this->model = $model;
	}
}