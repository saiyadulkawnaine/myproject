<?php
namespace App\Repositories\Implementations\Eloquent\Production\AOP;

use App\Repositories\Contracts\Production\AOP\ProdAopBatchRepository;
use App\Model\Production\AOP\ProdAopBatch;
use App\Traits\Eloquent\MsTraits;

class ProdAopBatchImplementation implements ProdAopBatchRepository
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
	public function __construct(ProdAopBatch $model)
	{
		$this->model = $model;
	}
}
