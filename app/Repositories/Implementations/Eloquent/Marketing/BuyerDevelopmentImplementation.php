<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\BuyerDevelopmentRepository;
use App\Model\Marketing\BuyerDevelopment;
use App\Traits\Eloquent\MsTraits;
class BuyerDevelopmentImplementation implements BuyerDevelopmentRepository
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
	public function __construct(BuyerDevelopment $model)
	{
		$this->model = $model;
	}
}
