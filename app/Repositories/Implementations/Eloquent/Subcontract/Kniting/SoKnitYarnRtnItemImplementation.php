<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Kniting;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitYarnRtnItemRepository;
use App\Model\Subcontract\Kniting\SoKnitYarnRtnItem;
use App\Traits\Eloquent\MsTraits;
class SoKnitYarnRtnItemImplementation implements SoKnitYarnRtnItemRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * SoKnitRefImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoKnitYarnRtnItem $model)
	{
		$this->model = $model;
	}
}
