<?php

namespace App\Repositories\Implementations\Eloquent\Bom;
use App\Repositories\Contracts\Bom\BudgetFabricProdConRepository;
use App\Model\Bom\BudgetFabricProdCon;
use App\Traits\Eloquent\MsTraits;
class BudgetFabricProdConImplementation implements BudgetFabricProdConRepository
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
	public function __construct(BudgetFabricProdCon $model)
	{
		$this->model = $model;
	}
}
