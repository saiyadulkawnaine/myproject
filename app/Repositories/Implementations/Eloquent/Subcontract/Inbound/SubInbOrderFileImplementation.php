<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Inbound;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbOrderFileRepository;
use App\Model\Subcontract\Inbound\SubInbOrderFile;
use App\Traits\Eloquent\MsTraits;
class SubInbOrderFileImplementation implements SubInbOrderFileRepository
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
	public function __construct(SubInbOrderFile $model)
	{
		$this->model = $model;
	}
}
