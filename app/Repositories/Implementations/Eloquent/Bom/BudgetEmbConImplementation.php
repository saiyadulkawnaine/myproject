<?php

namespace App\Repositories\Implementations\Eloquent\Bom;
use App\Repositories\Contracts\Bom\BudgetEmbConRepository;
use App\Model\Bom\BudgetEmbCon;
use App\Traits\Eloquent\MsTraits;
class BudgetEmbConImplementation implements BudgetEmbConRepository
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
	public function __construct(BudgetEmbCon $model)
	{
		$this->model = $model;
	}
}
