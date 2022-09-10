<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Dyeing;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingBomOverheadRepository;
use App\Model\Subcontract\Dyeing\SoDyeingBomOverhead;
use App\Traits\Eloquent\MsTraits;
class SoDyeingBomOverheadImplementation implements SoDyeingBomOverheadRepository
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
	public function __construct(SoDyeingBomOverhead $model)
	{
		$this->model = $model;
	}
}
