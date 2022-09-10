<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\BuyerDevelopmentOrderRepository;
use App\Model\Marketing\BuyerDevelopmentOrder;
use App\Traits\Eloquent\MsTraits;
class BuyerDevelopmentOrderImplementation implements BuyerDevelopmentOrderRepository
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
	public function __construct(BuyerDevelopmentOrder $model)
	{
		$this->model = $model;
	}
}
