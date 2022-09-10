<?php

namespace App\Repositories\Implementations\Eloquent\Bom;
use App\Repositories\Contracts\Bom\BudgetFabricConRepository;
use App\Model\Bom\BudgetFabricCon;
use App\Traits\Eloquent\MsTraits;
class BudgetFabricConImplementation implements BudgetFabricConRepository
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
	public function __construct(BudgetFabricCon $model)
	{
		$this->model = $model;
	}
}
