<?php
 
namespace App\Repositories\Implementations\Eloquent\System\Auth;
use App\Repositories\Contracts\System\Auth\RoleRepository;
use jeremykenedy\LaravelRoles\Models\Role;
use App\Traits\Eloquent\MsTraits; 
class RoleImplementation implements RoleRepository
{
	use MsTraits;
	/**
	 * @var $model
	 */
	private $model;
 
	/**
	 * MsSysRoleImplementation constructor.
	 *
	 * @param jeremykenedy\LaravelRoles\Models\Role $model
	 */
	public function __construct(Role $model)
	{
		$this->model = $model;
	}
}