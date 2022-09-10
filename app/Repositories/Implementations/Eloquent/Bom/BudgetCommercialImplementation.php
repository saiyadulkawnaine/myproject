<?php

namespace App\Repositories\Implementations\Eloquent\Bom;
use App\Repositories\Contracts\Bom\BudgetCommercialRepository;
use App\Model\Bom\BudgetCommercial;
use App\Traits\Eloquent\MsTraits;
class BudgetCommercialImplementation implements BudgetCommercialRepository
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
	public function __construct(BudgetCommercial $model)
	{
		$this->model = $model;
	}
}
