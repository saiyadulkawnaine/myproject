<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\StyleSizeRepository;
use App\Model\Marketing\StyleSize;
use App\Traits\Eloquent\MsTraits;
class StyleSizeImplementation implements StyleSizeRepository
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
	public function __construct(StyleSize $model)
	{
		$this->model = $model;
	}
}
