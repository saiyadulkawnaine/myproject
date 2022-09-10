<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Dyeing;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingDlvRepository;
use App\Model\Subcontract\Dyeing\SoDyeingDlv;
use App\Traits\Eloquent\MsTraits;
class SoDyeingDlvImplementation implements SoDyeingDlvRepository
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
	public function __construct(SoDyeingDlv $model)
	{
		$this->model = $model;
	}
}
