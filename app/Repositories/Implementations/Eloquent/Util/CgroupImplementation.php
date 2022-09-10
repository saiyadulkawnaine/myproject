<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\CgroupRepository;
use App\Model\Util\Cgroup;
use App\Traits\Eloquent\MsTraits; 
class CgroupImplementation implements CgroupRepository
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
	public function __construct(Cgroup $model)
	{
		$this->model = $model;
	}
}