<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\SupplierUserRepository;
use App\Model\Util\SupplierUser;
use App\Traits\Eloquent\MsTraits; 
class SupplierUserImplementation implements SupplierUserRepository
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
	public function __construct(SupplierUser $model)
	{
		$this->model = $model;
	}
	
	
}