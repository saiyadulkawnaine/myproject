<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Inbound;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbOrderProductRepository;
use App\Model\Subcontract\Inbound\SubInbOrderProduct;
use App\Traits\Eloquent\MsTraits;
class SubInbOrderProductImplementation implements SubInbOrderProductRepository
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
	public function __construct(SubInbOrderProduct $model)
	{
		$this->model = $model;
	}
}
