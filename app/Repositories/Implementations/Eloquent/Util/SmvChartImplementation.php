<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\SmvChartRepository;
use App\Model\Util\SmvChart;
use App\Traits\Eloquent\MsTraits;
class SmvChartImplementation implements SmvChartRepository
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
	public function __construct(SmvChart $model)
	{
		$this->model = $model;
	}
}
