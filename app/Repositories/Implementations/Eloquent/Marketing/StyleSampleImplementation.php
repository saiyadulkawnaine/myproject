<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\StyleSampleRepository;
use App\Model\Marketing\StyleSample;
use App\Traits\Eloquent\MsTraits;
class StyleSampleImplementation implements StyleSampleRepository
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
	public function __construct(StyleSample $model)
	{
		$this->model = $model;
	}
}
