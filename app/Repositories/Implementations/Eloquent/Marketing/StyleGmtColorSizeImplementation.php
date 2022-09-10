<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\StyleGmtColorSizeRepository;
use App\Model\Marketing\StyleGmtColorSize;
use App\Traits\Eloquent\MsTraits;
class StyleGmtColorSizeImplementation implements StyleGmtColorSizeRepository
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
	public function __construct(StyleGmtColorSize $model)
	{
		$this->model = $model;
	}
}
