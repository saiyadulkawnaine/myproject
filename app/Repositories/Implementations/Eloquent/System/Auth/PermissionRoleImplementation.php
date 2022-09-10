<?php
 
namespace App\Repositories\Implementations\Eloquent\System\Auth;
use App\Repositories\Contracts\System\Auth\PermissionRoleRepository;
use App\Model\System\PermissionRole;
use App\Traits\Eloquent\MsTraits; 
class PermissionRoleImplementation implements PermissionRoleRepository
{
	use MsTraits;
	/**
	 * @var $model
	 */
	private $model;
 
	/**
	 * MsSysRoleImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(PermissionRole $model)
	{
		$this->model = $model;
	}
}