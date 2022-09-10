<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\ItemAccountRatioRepository;
use App\Model\Util\ItemAccountRatio;
use App\Traits\Eloquent\MsTraits;
class ItemAccountRatioImplementation implements ItemAccountRatioRepository
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
	public function __construct(ItemAccountRatio $model)
	{
		$this->model = $model;
	}
}
