<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Kniting;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitItemRepository;
use App\Model\Subcontract\Kniting\SoKnitItem;
use App\Traits\Eloquent\MsTraits;
class SoKnitItemImplementation implements SoKnitItemRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * SoKnitProductImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoKnitItem $model)
	{
		$this->model = $model;
	}
}
