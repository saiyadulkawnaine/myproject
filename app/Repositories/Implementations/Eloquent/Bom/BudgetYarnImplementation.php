<?php

namespace App\Repositories\Implementations\Eloquent\Bom;
use App\Repositories\Contracts\Bom\BudgetYarnRepository;
use App\Model\Bom\BudgetYarn;
use App\Traits\Eloquent\MsTraits;
class BudgetYarnImplementation implements BudgetYarnRepository
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
	public function __construct(BudgetYarn $model)
	{
		$this->model = $model;
	}
}
