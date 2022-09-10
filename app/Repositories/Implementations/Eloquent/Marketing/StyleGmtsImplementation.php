<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\StyleGmtsRepository;
use App\Model\Marketing\StyleGmts;
use App\Traits\Eloquent\MsTraits;
class StyleGmtsImplementation implements StyleGmtsRepository
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
	public function __construct(StyleGmts $model)
	{
		$this->model = $model;
	}
}
