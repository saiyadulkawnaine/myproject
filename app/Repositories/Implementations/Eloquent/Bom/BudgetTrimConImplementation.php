<?php

namespace App\Repositories\Implementations\Eloquent\Bom;
use App\Repositories\Contracts\Bom\BudgetTrimConRepository;
use App\Model\Bom\BudgetTrimCon;
use App\Traits\Eloquent\MsTraits;
class BudgetTrimConImplementation implements BudgetTrimConRepository
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
	public function __construct(BudgetTrimCon $model)
	{
		$this->model = $model;
	}
}
