<?php

namespace App\Repositories\Implementations\Eloquent\Bom;
use App\Repositories\Contracts\Bom\BudgetFabricRepository;
use App\Model\Bom\BudgetFabric;
use App\Traits\Eloquent\MsTraits;
class BudgetFabricImplementation implements BudgetFabricRepository
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
	public function __construct(BudgetFabric $model)
	{
		$this->model = $model;
	}


}
