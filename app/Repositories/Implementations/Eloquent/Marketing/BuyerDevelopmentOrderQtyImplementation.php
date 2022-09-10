<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\BuyerDevelopmentOrderQtyRepository;
use App\Model\Marketing\BuyerDevelopmentOrderQty;
use App\Traits\Eloquent\MsTraits;
class BuyerDevelopmentOrderQtyImplementation implements BuyerDevelopmentOrderQtyRepository
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
	public function __construct(BuyerDevelopmentOrderQty $model)
	{
		$this->model = $model;
	}
}
