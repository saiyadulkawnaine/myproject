<?php

namespace App\Repositories\Implementations\Eloquent\Sales;
use App\Repositories\Contracts\Sales\ProjectionQtyRepository;
use App\Model\Sales\ProjectionQty;
use App\Traits\Eloquent\MsTraits;
class ProjectionQtyImplementation implements ProjectionQtyRepository
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
	public function __construct(ProjectionQty $model)
	{
		$this->model = $model;
	}
}
