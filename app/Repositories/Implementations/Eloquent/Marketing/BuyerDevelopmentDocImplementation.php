<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\BuyerDevelopmentDocRepository;
use App\Model\Marketing\BuyerDevelopmentDoc;
use App\Traits\Eloquent\MsTraits;
class BuyerDevelopmentDocImplementation implements BuyerDevelopmentDocRepository
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
	public function __construct(BuyerDevelopmentDoc $model)
	{
		$this->model = $model;
	}
}
