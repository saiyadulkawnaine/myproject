<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Dyeing;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingFabricRcvItemRepository;
use App\Model\Subcontract\Dyeing\SoDyeingFabricRcvItem;
use App\Traits\Eloquent\MsTraits;
class SoDyeingFabricRcvItemImplementation implements SoDyeingFabricRcvItemRepository
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
	public function __construct(SoDyeingFabricRcvItem $model)
	{
		$this->model = $model;
	}
}
