<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\StyleFabricationAopRepository;
use App\Model\Marketing\StyleFabricationAop;
use App\Traits\Eloquent\MsTraits;
class StyleFabricationAopImplementation implements StyleFabricationAopRepository
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
	public function __construct(StyleFabricationAop $model)
	{
		$this->model = $model;
	}
}
