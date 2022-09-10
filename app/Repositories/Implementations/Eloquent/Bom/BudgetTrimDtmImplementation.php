<?php

namespace App\Repositories\Implementations\Eloquent\Bom;
use App\Repositories\Contracts\Bom\BudgetTrimDtmRepository;
use App\Model\Bom\BudgetTrimDtm;
use App\Traits\Eloquent\MsTraits;
class BudgetTrimDtmImplementation implements BudgetTrimDtmRepository
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
	public function __construct(BudgetTrimDtm $model)
	{
		$this->model = $model;
	}
}
