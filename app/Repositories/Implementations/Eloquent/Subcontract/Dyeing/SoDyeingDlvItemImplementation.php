<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Dyeing;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingDlvItemRepository;
use App\Model\Subcontract\Dyeing\SoDyeingDlvItem;
use App\Traits\Eloquent\MsTraits;
class SoDyeingDlvItemImplementation implements SoDyeingDlvItemRepository
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
	public function __construct(SoDyeingDlvItem $model)
	{
		$this->model = $model;
	}
}
