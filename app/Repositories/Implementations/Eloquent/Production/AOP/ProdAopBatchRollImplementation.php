<?php
namespace App\Repositories\Implementations\Eloquent\Production\AOP;

use App\Repositories\Contracts\Production\AOP\ProdAopBatchRollRepository;
use App\Model\Production\AOP\ProdAopBatchRoll;
use App\Traits\Eloquent\MsTraits;

class ProdAopBatchRollImplementation implements ProdAopBatchRollRepository
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
	public function __construct(ProdAopBatchRoll $model)
	{
		$this->model = $model;
	}
}
