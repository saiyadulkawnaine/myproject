<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\BuyerDevelopmentIntmRepository;
use App\Model\Marketing\BuyerDevelopmentIntm;
use App\Traits\Eloquent\MsTraits;
class BuyerDevelopmentIntmImplementation implements BuyerDevelopmentIntmRepository
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
	public function __construct(BuyerDevelopmentIntm $model)
	{
		$this->model = $model;
	}
}
