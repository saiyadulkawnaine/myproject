<?php

namespace App\Repositories\Implementations\Eloquent\Bom;
use App\Repositories\Contracts\Bom\BudgetTrimRepository;
use App\Model\Bom\BudgetTrim;
use App\Traits\Eloquent\MsTraits;
class BudgetTrimImplementation implements BudgetTrimRepository
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
	public function __construct(BudgetTrim $model)
	{
		$this->model = $model;
	}
}
