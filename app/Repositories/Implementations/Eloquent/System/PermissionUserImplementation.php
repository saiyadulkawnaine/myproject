<?php
 
namespace App\Repositories\Implementations\Eloquent\System;
use App\Repositories\Contracts\System\PermissionUserRepository;
use App\Model\System\PermissionUser;
use App\Traits\Eloquent\MsTraits;  
class PermissionUserImplementation implements PermissionUserRepository
{
	 use MsTraits;
	/**
	 * @var $model
	 */
	private $model;
 
	/**
	 * Permission constructor.
	 *
	 * @param jeremykenedy\LaravelRoles\Models\Permission $model
	 */
	public function __construct(PermissionUser $model)
	{
		$this->model = $model;
	}
}