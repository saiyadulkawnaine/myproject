<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Embelishment;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbItemRepository;
use App\Model\Subcontract\Embelishment\SoEmbItem;
use App\Traits\Eloquent\MsTraits;
class SoEmbItemImplementation implements SoEmbItemRepository
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
	public function __construct(SoEmbItem $model)
	{
		$this->model = $model;
	}
}
