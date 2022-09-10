<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\StylePkgRepository;
use App\Model\Marketing\StylePkg;
use App\Traits\Eloquent\MsTraits;
class StylePkgImplementation implements StylePkgRepository
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
	public function __construct(StylePkg $model)
	{
		$this->model = $model;
	}
}
