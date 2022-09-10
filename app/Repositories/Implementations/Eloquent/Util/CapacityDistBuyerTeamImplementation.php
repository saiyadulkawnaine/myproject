<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\CapacityDistBuyerTeamRepository;
use App\Model\Util\CapacityDistBuyerTeam;
use App\Traits\Eloquent\MsTraits;
class CapacityDistBuyerTeamImplementation implements CapacityDistBuyerTeamRepository
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
	public function __construct(CapacityDistBuyerTeam $model)
	{
		$this->model = $model;
	}
}
