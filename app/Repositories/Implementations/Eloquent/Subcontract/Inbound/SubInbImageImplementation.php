<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Inbound;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbImageRepository;
use App\Model\Subcontract\Inbound\SubInbImage;
use App\Traits\Eloquent\MsTraits;
class SubInbImageImplementation implements SubInbImageRepository
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
	public function __construct(SubInbImage $model)
	{
		$this->model = $model;
	}
}
