<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\TeamRepository;
use App\Model\Util\Team;
use App\Traits\Eloquent\MsTraits; 
class TeamImplementation implements TeamRepository
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
	public function __construct(Team $model)
	{
		$this->model = $model;
	}
}