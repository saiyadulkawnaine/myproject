<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\GroupRepository;
use App\Model\Util\Group;
use App\Traits\Eloquent\MsTraits; 
class GroupImplementation implements GroupRepository
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
	public function __construct(Group $model)
	{
		$this->model = $model;
	}
}