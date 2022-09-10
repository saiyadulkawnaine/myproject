<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\MktCostQuotePriceRepository;
use App\Model\Marketing\MktCostQuotePrice;
use App\Traits\Eloquent\MsTraits;
class MktCostQuotePriceImplementation implements MktCostQuotePriceRepository
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
	public function __construct(MktCostQuotePrice $model)
	{
		$this->model = $model;
	}
}
