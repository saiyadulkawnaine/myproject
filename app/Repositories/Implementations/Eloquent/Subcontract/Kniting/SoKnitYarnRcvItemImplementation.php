<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Kniting;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitYarnRcvItemRepository;
use App\Model\Subcontract\Kniting\SoKnitYarnRcvItem;
use App\Traits\Eloquent\MsTraits;
class SoKnitYarnRcvItemImplementation implements SoKnitYarnRcvItemRepository
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
	public function __construct(SoKnitYarnRcvItem $model)
	{
		$this->model = $model;
	}
}
