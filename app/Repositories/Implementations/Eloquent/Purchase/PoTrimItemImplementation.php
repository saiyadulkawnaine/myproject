<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PoTrimItemRepository;
use App\Model\Purchase\PoTrimItem;
use App\Traits\Eloquent\MsTraits;
class PoTrimItemImplementation implements PoTrimItemRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * PoTrimItemImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(PoTrimItem $model)
	{
		$this->model = $model;
	}
}
