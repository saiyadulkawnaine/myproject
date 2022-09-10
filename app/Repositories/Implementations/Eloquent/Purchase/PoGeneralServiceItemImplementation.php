<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PoGeneralServiceItemRepository;
use App\Model\Purchase\PoGeneralServiceItem;
use App\Traits\Eloquent\MsTraits;
class PoGeneralServiceItemImplementation implements PoGeneralServiceItemRepository
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
	public function __construct(PoGeneralServiceItem $model)
	{
		$this->model = $model;
	}
}
