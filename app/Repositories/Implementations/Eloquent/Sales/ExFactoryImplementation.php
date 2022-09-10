<?php

namespace App\Repositories\Implementations\Eloquent\Sales;
use App\Repositories\Contracts\Sales\ExFactoryRepository;
use App\Model\Sales\ExFactory;
use App\Traits\Eloquent\MsTraits;
class ExFactoryImplementation implements ExFactoryRepository
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
	public function __construct(ExFactory $model)
	{
		$this->model = $model;
	}
}
