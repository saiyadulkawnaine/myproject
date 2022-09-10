<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\BuyerDevelopmentEventRepository;
use App\Model\Marketing\BuyerDevelopmentEvent;
use App\Traits\Eloquent\MsTraits;
class BuyerDevelopmentEventImplementation implements BuyerDevelopmentEventRepository
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
	public function __construct(BuyerDevelopmentEvent $model)
	{
		$this->model = $model;
	}
}
