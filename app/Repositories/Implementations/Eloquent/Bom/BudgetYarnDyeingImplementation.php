<?php

namespace App\Repositories\Implementations\Eloquent\Bom;
use App\Repositories\Contracts\Bom\BudgetYarnDyeingRepository;
use App\Model\Bom\BudgetYarnDyeing;
use App\Traits\Eloquent\MsTraits;
class BudgetYarnDyeingImplementation implements BudgetYarnDyeingRepository
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
	public function __construct(BudgetYarnDyeing $model)
	{
		$this->model = $model;
	}
}
