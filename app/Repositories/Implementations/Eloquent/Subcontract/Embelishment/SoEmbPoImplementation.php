<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Embelishment;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPoRepository;
use App\Model\Subcontract\Embelishment\SoEmbPo;
use App\Traits\Eloquent\MsTraits;
class SoEmbPoImplementation implements SoEmbPoRepository
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
	public function __construct(SoEmbPo $model)
	{
		$this->model = $model;
	}
}
