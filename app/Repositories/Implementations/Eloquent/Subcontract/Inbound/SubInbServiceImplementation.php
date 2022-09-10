<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Inbound;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbServiceRepository;
use App\Model\Subcontract\Inbound\SubInbService;
use App\Traits\Eloquent\MsTraits;
class SubInbServiceImplementation implements SubInbServiceRepository
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
	public function __construct(SubInbService $model)
	{
		$this->model = $model;
	}
}
