<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\StyleFabricationRepository;
use App\Model\Marketing\StyleFabrication;
use App\Traits\Eloquent\MsTraits;
class StyleFabricationImplementation implements StyleFabricationRepository
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
	public function __construct(StyleFabrication $model)
	{
		$this->model = $model;
	}
}
