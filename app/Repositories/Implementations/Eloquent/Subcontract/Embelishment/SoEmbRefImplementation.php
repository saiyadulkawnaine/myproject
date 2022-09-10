<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Embelishment;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbRefRepository;
use App\Model\Subcontract\Embelishment\SoEmbRef;
use App\Traits\Eloquent\MsTraits;
class SoEmbRefImplementation implements SoEmbRefRepository
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
	public function __construct(SoEmbRef $model)
	{
		$this->model = $model;
	}
}
