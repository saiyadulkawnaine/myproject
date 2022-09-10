<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Kniting;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitPoItemRepository;
use App\Model\Subcontract\Kniting\SoKnitPoItem;
use App\Traits\Eloquent\MsTraits;
class SoKnitPoItemImplementation implements SoKnitPoItemRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * SoKnitPoItemImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoKnitPoItem $model)
	{
		$this->model = $model;
	}
}
