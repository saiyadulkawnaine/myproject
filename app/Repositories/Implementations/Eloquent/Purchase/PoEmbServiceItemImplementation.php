<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PoEmbServiceItemRepository;
use App\Model\Purchase\PoEmbServiceItem;
use App\Traits\Eloquent\MsTraits;
class PoEmbServiceItemImplementation implements PoEmbServiceItemRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * PoEmbServiceItemImplementation constructor.
	 *
	 * @param App\Model\Purchase\PoEmbServiceItem $model
	 */
	public function __construct(PoEmbServiceItem $model)
	{
		$this->model = $model;
	}
}
