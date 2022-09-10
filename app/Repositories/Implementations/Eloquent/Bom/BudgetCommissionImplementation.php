<?php

namespace App\Repositories\Implementations\Eloquent\Bom;
use App\Repositories\Contracts\Bom\BudgetCommissionRepository;
use App\Model\Bom\BudgetCommission;
use App\Traits\Eloquent\MsTraits;
class BudgetCommissionImplementation implements BudgetCommissionRepository
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
	public function __construct(BudgetCommission $model)
	{
		$this->model = $model;
	}
}
