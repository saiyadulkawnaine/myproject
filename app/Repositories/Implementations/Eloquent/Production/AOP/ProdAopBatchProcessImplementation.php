<?php
namespace App\Repositories\Implementations\Eloquent\Production\AOP;

use App\Repositories\Contracts\Production\AOP\ProdAopBatchProcessRepository;
use App\Model\Production\AOP\ProdAopBatchProcess;
use App\Traits\Eloquent\MsTraits;

class ProdAopBatchProcessImplementation implements ProdAopBatchProcessRepository
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
	public function __construct(ProdAopBatchProcess $model)
	{
		$this->model = $model;
	}
}
