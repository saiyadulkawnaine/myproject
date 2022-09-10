<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Kniting;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitPoRepository;
use App\Model\Subcontract\Kniting\SoKnitPo;
use App\Traits\Eloquent\MsTraits;
class SoKnitPoImplementation implements SoKnitPoRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * SoKnitPoImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoKnitPo $model)
	{
		$this->model = $model;
	}
}
