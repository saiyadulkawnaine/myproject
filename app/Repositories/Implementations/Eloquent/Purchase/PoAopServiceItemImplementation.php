<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PoAopServiceItemRepository;
use App\Model\Purchase\PoAopServiceItem;
use App\Traits\Eloquent\MsTraits;
class PoAopServiceItemImplementation implements PoAopServiceItemRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * PoAopServiceItemImplementation constructor.
	 *
	 * @param App\Model\Purchase\PoAopServiceItem $model
	 */
	public function __construct(PoAopServiceItem $model)
	{
		$this->model = $model;
	}
}
