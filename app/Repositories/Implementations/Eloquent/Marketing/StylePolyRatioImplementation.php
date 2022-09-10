<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\StylePolyRatioRepository;
use App\Model\Marketing\StylePolyRatio;
use App\Traits\Eloquent\MsTraits;
class StylePolyRatioImplementation implements StylePolyRatioRepository
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
	public function __construct(StylePolyRatio $model)
	{
		$this->model = $model;
	}
}
