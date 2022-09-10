<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\TeammemberRepository;
use App\Model\Util\Teammember;
use App\Traits\Eloquent\MsTraits; 
class TeammemberImplementation implements TeammemberRepository
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
	public function __construct(Teammember $model)
	{
		$this->model = $model;
	}
}