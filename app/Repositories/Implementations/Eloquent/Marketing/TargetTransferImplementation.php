<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\TargetTransferRepository;
use App\Model\Marketing\TargetTransfer;
use App\Traits\Eloquent\MsTraits;
class TargetTransferImplementation implements TargetTransferRepository
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
	public function __construct(TargetTransfer $model)
	{
		$this->model = $model;
	}
}
