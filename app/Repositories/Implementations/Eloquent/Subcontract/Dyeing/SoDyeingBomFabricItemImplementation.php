<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Dyeing;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingBomFabricItemRepository;
use App\Model\Subcontract\Dyeing\SoDyeingBomFabricItem;
use App\Traits\Eloquent\MsTraits;
class SoDyeingBomFabricItemImplementation implements SoDyeingBomFabricItemRepository
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
	public function __construct(SoDyeingBomFabricItem $model)
	{
		$this->model = $model;
	}
}
