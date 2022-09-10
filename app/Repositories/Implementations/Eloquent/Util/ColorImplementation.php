<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Model\Util\Color;
use App\Traits\Eloquent\MsTraits;
class ColorImplementation implements ColorRepository
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
	public function __construct(Color $model)
	{
		$this->model = $model;
	}
}
