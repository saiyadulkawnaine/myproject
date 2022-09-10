<?php
namespace App\Repositories\Implementations\Eloquent\Bom;
use App\Repositories\Contracts\Bom\BudgetCmRepository;
use App\Model\Bom\BudgetCm;
use App\Traits\Eloquent\MsTraits;
class BudgetCmImplementation implements BudgetCmRepository
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
	public function __construct(BudgetCm $model)
	{
		$this->model = $model;
	}
}
