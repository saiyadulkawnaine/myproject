<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\DayTargetTransferRepository;
use App\Model\Marketing\DayTargetTransfer;
use App\Traits\Eloquent\MsTraits;
class DayTargetTransferImplementation implements DayTargetTransferRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * TargetTransferImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(DayTargetTransfer $model)
	{
		$this->model = $model;
	}
}
