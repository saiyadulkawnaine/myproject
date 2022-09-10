<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Kniting;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitDlvRepository;
use App\Model\Subcontract\Kniting\SoKnitDlv;
use App\Traits\Eloquent\MsTraits;
class SoKnitDlvImplementation implements SoKnitDlvRepository
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
	public function __construct(SoKnitDlv $model)
	{
		$this->model = $model;
	}
}
