<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\YarntypeRepository;
use App\Model\Util\Yarntype;
use App\Traits\Eloquent\MsTraits;
class YarntypeImplementation implements YarntypeRepository
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
	public function __construct(Yarntype $model)
	{
		$this->model = $model;
	}
}
