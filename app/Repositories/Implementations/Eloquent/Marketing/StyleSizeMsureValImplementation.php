<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\StyleSizeMsureValRepository;
use App\Model\Marketing\StyleSizeMsureVal;
use App\Traits\Eloquent\MsTraits;
class StyleSizeMsureValImplementation implements StyleSizeMsureValRepository
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
	public function __construct(StyleSizeMsureVal $model)
	{
		$this->model = $model;
	}
}
