<?php
 
namespace App\Repositories\Implementations\Eloquent\System;
use App\Repositories\Contracts\System\PermissionRepository;
use jeremykenedy\LaravelRoles\Models\Permission;
use App\Traits\Eloquent\MsTraits;  
class PermissionImplementation implements PermissionRepository
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
	public function __construct(Permission $model)
	{
		$this->model = $model;
	}
}