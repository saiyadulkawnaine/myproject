<?php
 
namespace App\Repositories\Implementations\Eloquent\System\Auth;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\User;
use jeremykenedy\LaravelRoles\Models\Role;
use App\Traits\Eloquent\MsTraits; 
class UserImplementation implements UserRepository
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
	public function __construct(User $model)
	{
		$this->model = $model;
	}
}