<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Dyeing;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingRefRepository;
use App\Model\Subcontract\Dyeing\SoDyeingRef;
use App\Traits\Eloquent\MsTraits;
class SoDyeingRefImplementation implements SoDyeingRefRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * SoDyeingRefImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoDyeingRef $model)
	{
		$this->model = $model;
	}
}
