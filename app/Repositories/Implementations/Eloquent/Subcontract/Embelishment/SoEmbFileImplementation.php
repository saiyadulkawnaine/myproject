<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Embelishment;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbFileRepository;
use App\Model\Subcontract\Embelishment\SoEmbFile;
use App\Traits\Eloquent\MsTraits;
class SoEmbFileImplementation implements SoEmbFileRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * SoKnitFileImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoEmbFile $model)
	{
		$this->model = $model;
	}
}
