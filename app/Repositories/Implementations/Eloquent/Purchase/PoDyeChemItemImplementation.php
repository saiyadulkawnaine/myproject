<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PoDyeChemItemRepository;
use App\Model\Purchase\PoDyeChemItem;
use App\Traits\Eloquent\MsTraits;
class PoDyeChemItemImplementation implements PoDyeChemItemRepository
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
	public function __construct(PoDyeChemItem $model)
	{
		$this->model = $model;
	}
}
