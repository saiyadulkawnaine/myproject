<?php

namespace App\Repositories\Implementations\Eloquent\Bom;
use App\Repositories\Contracts\Bom\BudgetYarnDyeingConRepository;
use App\Model\Bom\BudgetYarnDyeingCon;
use App\Traits\Eloquent\MsTraits;
class BudgetYarnDyeingConImplementation implements BudgetYarnDyeingConRepository
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
	public function __construct(BudgetYarnDyeingCon $model)
	{
		$this->model = $model;
	}
}
