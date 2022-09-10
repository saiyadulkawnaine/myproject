<?php

namespace App\Repositories\Implementations\Eloquent\Bom;
use App\Repositories\Contracts\Bom\BudgetFabricProdRepository;
use App\Model\Bom\BudgetFabricProd;
use App\Traits\Eloquent\MsTraits;
class BudgetFabricProdImplementation implements BudgetFabricProdRepository
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
	public function __construct(BudgetFabricProd $model)
	{
		$this->model = $model;
	}
}
