<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Embelishment;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPoItemRepository;
use App\Model\Subcontract\Embelishment\SoEmbPoItem;
use App\Traits\Eloquent\MsTraits;
class SoEmbPoItemImplementation implements SoEmbPoItemRepository
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
	public function __construct(SoEmbPoItem $model)
	{
		$this->model = $model;
	}
}
