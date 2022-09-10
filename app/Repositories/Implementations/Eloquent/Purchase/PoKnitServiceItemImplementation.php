<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PoKnitServiceItemRepository;
use App\Model\Purchase\PoKnitServiceItem;
use App\Traits\Eloquent\MsTraits;
class PoKnitServiceItemImplementation implements PoKnitServiceItemRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * PoKnitServiceItemImplementation constructor.
	 *
	 * @param App\Model\Purchase\PoKnitServiceItem $model
	 */
	public function __construct(PoKnitServiceItem $model)
	{
		$this->model = $model;
	}
}
