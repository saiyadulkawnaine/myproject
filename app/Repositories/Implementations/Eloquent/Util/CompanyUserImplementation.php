<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\CompanyUserRepository;
use App\Model\Util\CompanyUser;
use App\Traits\Eloquent\MsTraits; 
class CompanyUserImplementation implements CompanyUserRepository
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
	public function __construct(CompanyUser $model)
	{
		$this->model = $model;
	}
}