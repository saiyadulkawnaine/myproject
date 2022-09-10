<?php

namespace App\Repositories\Implementations\Eloquent\Sales;
use App\Repositories\Contracts\Sales\ProjectionRepository;
use App\Model\Sales\Projection;
use App\Traits\Eloquent\MsTraits;
class ProjectionImplementation implements ProjectionRepository
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
	public function __construct(Projection $model)
	{
		$this->model = $model;
	}
}
