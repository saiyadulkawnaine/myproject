<?php

namespace App\Repositories\Implementations\Eloquent\Bom;
use App\Repositories\Contracts\Bom\BudgetOtherRepository;
use App\Model\Bom\BudgetOther;
use App\Traits\Eloquent\MsTraits;
class BudgetOtherImplementation implements BudgetOtherRepository
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
	public function __construct(BudgetOther $model)
	{
		$this->model = $model;
	}
}
