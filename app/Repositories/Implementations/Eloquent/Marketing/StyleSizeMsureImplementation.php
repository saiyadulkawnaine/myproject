<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\StyleSizeMsureRepository;
use App\Model\Marketing\StyleSizeMsure;
use App\Traits\Eloquent\MsTraits;
class StyleSizeMsureImplementation implements StyleSizeMsureRepository
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
	public function __construct(StyleSizeMsure $model)
	{
		$this->model = $model;
	}
}
