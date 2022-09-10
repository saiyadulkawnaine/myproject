<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\SizeRepository;
use App\Model\Util\Size;
use App\Traits\Eloquent\MsTraits;
class SizeImplementation implements SizeRepository
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
	public function __construct(Size $model)
	{
		$this->model = $model;
	}
}
