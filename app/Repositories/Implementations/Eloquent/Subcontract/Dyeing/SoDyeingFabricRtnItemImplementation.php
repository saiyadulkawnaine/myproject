<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Dyeing;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingFabricRtnItemRepository;
use App\Model\Subcontract\Dyeing\SoDyeingFabricRtnItem;
use App\Traits\Eloquent\MsTraits;
class SoDyeingFabricRtnItemImplementation implements SoDyeingFabricRtnItemRepository
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
	public function __construct(SoDyeingFabricRtnItem $model)
	{
		$this->model = $model;
	}
}
