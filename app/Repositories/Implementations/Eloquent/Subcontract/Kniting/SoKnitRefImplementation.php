<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Kniting;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitRefRepository;
use App\Model\Subcontract\Kniting\SoKnitRef;
use App\Traits\Eloquent\MsTraits;
class SoKnitRefImplementation implements SoKnitRefRepository
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
	public function __construct(SoKnitRef $model)
	{
		$this->model = $model;
	}
}
