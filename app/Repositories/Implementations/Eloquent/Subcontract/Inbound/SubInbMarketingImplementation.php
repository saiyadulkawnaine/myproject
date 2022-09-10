<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Inbound;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbMarketingRepository;
use App\Model\Subcontract\Inbound\SubInbMarketing;
use App\Traits\Eloquent\MsTraits;
class SubInbMarketingImplementation implements SubInbMarketingRepository
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
	public function __construct(SubInbMarketing $model)
	{
		$this->model = $model;
	}
}
