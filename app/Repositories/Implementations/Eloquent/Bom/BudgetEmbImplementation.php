<?php

namespace App\Repositories\Implementations\Eloquent\Bom;
use App\Repositories\Contracts\Bom\BudgetEmbRepository;
use App\Model\Bom\BudgetEmb;
use App\Traits\Eloquent\MsTraits;
class BudgetEmbImplementation implements BudgetEmbRepository
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
	public function __construct(BudgetEmb $model)
	{
		$this->model = $model;
	}
}
