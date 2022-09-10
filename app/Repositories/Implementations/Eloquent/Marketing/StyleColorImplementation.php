<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\StyleColorRepository;
use App\Model\Marketing\StyleColor;
use App\Traits\Eloquent\MsTraits;
class StyleColorImplementation implements StyleColorRepository
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
	public function __construct(StyleColor $model)
	{
		$this->model = $model;
	}
}
