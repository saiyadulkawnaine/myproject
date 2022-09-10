<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PoGeneralItemRepository;
use App\Model\Purchase\PoGeneralItem;
use App\Traits\Eloquent\MsTraits;
class PoGeneralItemImplementation implements PoGeneralItemRepository
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
	public function __construct(PoGeneralItem $model)
	{
		$this->model = $model;
	}
}
