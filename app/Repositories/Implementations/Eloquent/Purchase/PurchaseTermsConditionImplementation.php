<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PurchaseTermsConditionRepository;
use App\Model\Purchase\PurchaseTermsCondition;
use App\Traits\Eloquent\MsTraits;
class PurchaseTermsConditionImplementation implements PurchaseTermsConditionRepository
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
	public function __construct(PurchaseTermsCondition $model)
	{
		$this->model = $model;
	}
}
