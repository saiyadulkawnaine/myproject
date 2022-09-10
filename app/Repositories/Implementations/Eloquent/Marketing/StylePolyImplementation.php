<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\StylePolyRepository;
use App\Model\Marketing\StylePoly;
use App\Traits\Eloquent\MsTraits;
class StylePolyImplementation implements StylePolyRepository
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
	public function __construct(StylePoly $model)
	{
		$this->model = $model;
	}
}
