<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\AOP;
use App\Repositories\Contracts\Subcontract\AOP\SoAopFileRepository;
use App\Model\Subcontract\AOP\SoAopFile;
use App\Traits\Eloquent\MsTraits;
class SoAopFileImplementation implements SoAopFileRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * SoAopFileImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoAopFile $model)
	{
		$this->model = $model;
	}
}
